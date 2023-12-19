<?php
declare(strict_types = 1);

namespace App\Http\Controllers\SmsBuilder;

use Exception;
use Illuminate\Support\Facades\Auth;
use smpp\{Address, Client as SmppClient, Smpp, transport\Socket};

class SmsBuilder
{
    /** @var string 11 chars limit */
    // public const DEFAULT_SENDER = 'Datasur';
    public const DEFAULT_SENDER = 'SMS Portal';

    protected string $defaultSender;

    protected Socket $transport;

    protected SmppClient $smppClient;

    protected bool $debug = false;

    protected Address $from;

    protected Address $to;

    protected string $login;

    protected string $password;

    /**
     * SmsBuilder constructor.
     *
     * @param string $address SMSC IP
     * @param int $port SMSC port
     * @param string $login
     * @param string $password
     * @param int $timeout timeout of reading PDU in milliseconds
     * @param object $user User object
     * @param bool $debug - debug flag when true output additional info
     */
    public function __construct(
        string $address,
        int $port,
        string $login,
        string $password,
        object $user,
        int $timeout = 10000,
        bool $debug = false
    ) {
        $this->transport = new Socket([$address], $port);
        // Activate binary hex-output of server interaction
        $this->transport->debug = $debug;
        $this->transport->setRecvTimeout($timeout);
        $this->smppClient = new SmppClient($this->transport);

        $this->login = $login;
        $this->password = $password;

        $this->from = new Address($user->organizations->default_sender ? $user->organizations->default_sender : self::DEFAULT_SENDER, SMPP::TON_ALPHANUMERIC);
    }

    /**
     * @param string $sender
     * @param int $ton
     *
     * @return $this
     * @throws Exception
     */
    public function setSender(string $sender, int $ton): SmsBuilder
    {
        return $this->setAddress($sender, 'from', $ton);
    }

    /**
     * @param string $address
     * @param string $type
     * @param int $ton
     * @param int $npi
     *
     * @return $this
     * @throws Exception
     */
    protected function setAddress(
        string $address,
        string $type,
        int $ton = SMPP::TON_UNKNOWN,
        int $npi = SMPP::NPI_UNKNOWN
    ): SmsBuilder {
        // some example of data preparation
        if ($ton === SMPP::TON_INTERNATIONAL) {
            $npi = SMPP::NPI_E164;
        }
        $this->$type = new Address($address, $ton, $npi);

        return $this;
    }

    /**
     * @param string $address
     * @param int $ton
     *
     * @return $this
     * @throws Exception
     */
    public function setRecipient(string $address, int $ton): SmsBuilder
    {
        return $this->setAddress($address, 'to', $ton);
    }

    /**
     * @param string $message
     *
     * @throws Exception
     */
    public function sendMessage(string $message): void
    {
        $this->transport->open();
        $this->smppClient->bindTransceiver($this->login, $this->password);
        // strongly recommend use SMPP::DATA_CODING_UCS2 as default encoding in project to prevent problems with non latin symbols
        $this->smppClient->sendSMS($this->from, $this->to, $message, null, SMPP::DATA_CODING_UCS2);
        $this->smppClient->close();
    }
}
