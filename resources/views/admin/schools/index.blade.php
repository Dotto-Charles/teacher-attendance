<x-admin-layout title="Schools">

<div class="acard">
    <div class="acard-header">
        <div>
            <div class="acard-title">Schools</div>
            <div class="acard-sub">All registered schools</div>
        </div>
    </div>

    <div class="acard-body">
        <div class="atable-wrap">
            <table class="atable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>School</th>
                        <th>Ward</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($schools as $school)
                    <tr>
                        <td>{{ $school->id }}</td>
                        <td>{{ $school->name }}</td>
                        <td>{{ $school->ward->name ?? '-' }}</td>
                        <td>
                            <form method="POST"
                                  action="{{ route('admin.schools.delete', $school) }}">
                                @csrf
                                @method('DELETE')

                                <button class="abtn abtn-red abtn-sm">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">No schools found</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

</x-admin-layout>