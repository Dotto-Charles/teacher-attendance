<x-admin-layout title="Dashboard">

<div class="admin-stats">

    <div class="astat ao">
        <div class="astat-icon">👥</div>
        <div class="astat-val">{{ $stats['total'] }}</div>
        <div class="astat-lbl">Total Users</div>
    </div>

    <div class="astat ag">
        <div class="astat-icon">✅</div>
        <div class="astat-val">{{ $stats['approved'] }}</div>
        <div class="astat-lbl">Approved</div>
    </div>

    <div class="astat ar">
        <div class="astat-icon">⏳</div>
        <div class="astat-val">{{ $stats['pending'] }}</div>
        <div class="astat-lbl">Pending</div>
    </div>

    <div class="astat ab">
        <div class="astat-icon">🏫</div>
        <div class="astat-val">{{ $stats['headteachers'] }}</div>
        <div class="astat-lbl">Head Teachers</div>
    </div>

</div>

<div class="acard">

    <div class="acard-header">
        <div>
            <div class="acard-title">
                Recent Users
            </div>
        </div>
    </div>

    <div class="atable-wrap">

        <table class="atable">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            @foreach($users as $user)

                <tr>

                    <td>
                        {{ $user->first_name }}
                        {{ $user->last_name }}
                    </td>

                    <td>{{ $user->email }}</td>

                    <td>{{ $user->role }}</td>

                    <td>{{ $user->status }}</td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

</x-admin-layout>