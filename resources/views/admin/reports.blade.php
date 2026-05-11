<x-admin-layout title="System Reports">

<div class="admin-stats">

    <div class="astat ao">
        <div class="astat-icon">👥</div>
        <div class="astat-val">
            {{ \App\Models\User::count() }}
        </div>
        <div class="astat-lbl">Total Users</div>
    </div>

    <div class="astat ag">
        <div class="astat-icon">✅</div>
        <div class="astat-val">
            {{ \App\Models\User::where('status','approved')->count() }}
        </div>
        <div class="astat-lbl">Approved Users</div>
    </div>

    <div class="astat ar">
        <div class="astat-icon">🚫</div>
        <div class="astat-val">
            {{ \App\Models\User::where('status','blocked')->count() }}
        </div>
        <div class="astat-lbl">Blocked Users</div>
    </div>

    <div class="astat ab">
        <div class="astat-icon">🏫</div>
        <div class="astat-val">
            {{ \App\Models\School::count() }}
        </div>
        <div class="astat-lbl">Schools</div>
    </div>

</div>

<div class="acard">
    <div class="acard-header">
        <div>
            <div class="acard-title">System Analytics</div>
            <div class="acard-sub">
                Mfumo overview na taarifa muhimu
            </div>
        </div>
    </div>

    <div class="acard-body">

        <table class="atable">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>Teachers</td>
                    <td>
                        {{ \App\Models\User::where('role','teacher')->count() }}
                    </td>
                </tr>

                <tr>
                    <td>Head Teachers</td>
                    <td>
                        {{ \App\Models\User::where('role','head_teacher')->count() }}
                    </td>
                </tr>

                <tr>
                    <td>Ward Officers</td>
                    <td>
                        {{ \App\Models\User::where('role','ward_officer')->count() }}
                    </td>
                </tr>

                <tr>
                    <td>District Officers</td>
                    <td>
                        {{ \App\Models\User::where('role','district_officer')->count() }}
                    </td>
                </tr>

                <tr>
                    <td>Pending Accounts</td>
                    <td>
                        {{ \App\Models\User::where('status','pending')->count() }}
                    </td>
                </tr>

            </tbody>
        </table>

    </div>
</div>

</x-admin-layout>