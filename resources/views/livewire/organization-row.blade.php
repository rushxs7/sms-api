<tr>
  <td>
    {{ $organization->name }}
    @if($organization->deleted_at)
    <span class="badge bg-danger">Disabled</span>
    @endif</td>
  <td>
    {{ $organization->default_sender }}
  </td>
  <td class="d-flex justify-content-end">
    @if(!$organization->trashed())
    <a href="{{ route('organizations.edit', ['organization' => $organization]) }}" class="btn btn-sm btn-outline-warning">
      <i class="fa-solid fa-pencil"></i>
    </a>
    @endif
    <button wire:click="toggleArchive" class="btn btn-sm btn-danger ms-1" data-bs-toggle="tooltip" data-bs-title="@if($organization->trashed()) Activeren @else Deactiveren @endif">
      @if($organization->trashed())
      <i class="fa-solid fa-trash-can-arrow-up"></i>
      @else
      <i class="fa-solid fa-trash"></i>
      @endif
    </button>
  </td>
</tr>
