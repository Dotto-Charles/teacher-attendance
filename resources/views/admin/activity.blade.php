<x-admin-layout title="Recent Activity">

<div class="acard">
    <div class="acard-header">
        <div>
            <div class="acard-title">Recent Activities</div>
            <div class="acard-sub">
                Mfumo activities za hivi karibuni
            </div>
        </div>
    </div>

    <div class="acard-body">

        <table class="atable">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>

                @foreach(\App\Models\User::latest()->take(20)->get() as $user)

                <tr>
                    <td>
                        {{ $user->first_name }}
                        {{ $user->last_name }}
                    </td>

                    <td>
                        <span class="abadge ab-blue">
                            {{ $user->role }}
                        </span>
                    </td>

                    <td>
                        <span class="abadge ab-green">
                            {{ $user->status }}
                        </span>
                    </td>

                    <td>
                        {{ $user->created_at->diffForHumans() }}
                    </td>
                </tr>

                @endforeach

            </tbody>
        </table>

    </div>
</div>

</x-admin-layout>