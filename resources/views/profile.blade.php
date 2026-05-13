{{-- resources/views/profile/edit.blade.php --}}

@extends('layouts.app')

@section('title', 'Wasifu Wangu')

@section('content')

<style>

    :root{
        --primary:#1d4ed8;
        --primary-light:#2563eb;
        --dark:#0f172a;
        --card:#ffffff;
        --border:#dbe4f0;
        --text:#0f172a;
        --muted:#64748b;
        --success:#16a34a;
        --danger:#dc2626;
        --bg:#f1f5f9;
    }

    body{
        background:var(--bg);
    }

    .profile-wrapper{
        max-width:1100px;
        margin:auto;
        padding:30px 15px 60px;
    }

    /* PAGE HEADER */

    .profile-header{
        background:
            linear-gradient(
                135deg,
                rgba(29,78,216,.95),
                rgba(37,99,235,.92)
            ),
            url('{{ asset("images/ppppppp.jpg") }}');

        background-size:cover;
        background-position:center;

        border-radius:24px;

        padding:40px 30px;

        color:white;

        margin-bottom:25px;

        position:relative;

        overflow:hidden;

        box-shadow:
            0 15px 40px rgba(0,0,0,.12);
    }

    .profile-header::before{
        content:'';
        position:absolute;
        inset:0;
        background:
            linear-gradient(
                rgba(255,255,255,.05),
                transparent
            );
    }

    .profile-top{
        position:relative;
        z-index:2;

        display:flex;
        align-items:center;
        gap:22px;
        flex-wrap:wrap;
    }

    .profile-avatar{
        width:95px;
        height:95px;

        border-radius:50%;

        background:white;

        color:var(--primary);

        display:flex;
        align-items:center;
        justify-content:center;

        font-size:34px;
        font-weight:800;

        border:4px solid rgba(255,255,255,.25);

        box-shadow:
            0 10px 25px rgba(0,0,0,.2);
    }

    .profile-info h1{
        margin:0;
        font-size:30px;
        font-weight:800;
    }

    .profile-info p{
        margin-top:8px;
        color:rgba(255,255,255,.9);
        font-size:15px;
    }

    .gov-badge{
        margin-top:14px;

        display:inline-flex;
        align-items:center;
        gap:8px;

        background:rgba(255,255,255,.12);

        padding:10px 15px;

        border-radius:999px;

        border:1px solid rgba(255,255,255,.15);

        font-size:13px;
        font-weight:600;
    }

    /* GRID */

    .profile-grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:22px;
    }

    /* CARD */

    .profile-card{
        background:var(--card);

        border-radius:22px;

        border:1px solid var(--border);

        overflow:hidden;

        box-shadow:
            0 8px 25px rgba(15,23,42,.05);
    }

    .card-header{
        padding:22px 24px;

        border-bottom:1px solid #eef2f7;

        background:
            linear-gradient(
                to right,
                rgba(37,99,235,.05),
                transparent
            );
    }

    .card-header h3{
        margin:0;
        font-size:20px;
        color:var(--dark);
        font-weight:800;

        display:flex;
        align-items:center;
        gap:10px;
    }

    .card-header p{
        margin-top:8px;
        color:var(--muted);
        font-size:14px;
    }

    .card-body{
        padding:25px;
    }

    /* LIVEWIRE FIX */

    .card-body form{
        width:100%;
    }

    .card-body .grid{
        display:grid !important;
        grid-template-columns:1fr 1fr;
        gap:16px;
    }

    .card-body .col-span-2{
        grid-column:span 2;
    }

    .card-body label{
        display:block;
        margin-bottom:8px;
        font-size:14px;
        font-weight:700;
        color:var(--dark);
    }

    .card-body input,
    .card-body textarea,
    .card-body select{
        width:100%;

        height:52px;

        border-radius:14px;

        border:1px solid #cbd5e1;

        padding:0 16px;

        font-size:15px;

        background:#fff;

        transition:.3s;
    }

    .card-body textarea{
        min-height:120px;
        padding-top:14px;
    }

    .card-body input:focus,
    .card-body textarea:focus,
    .card-body select:focus{
        outline:none;

        border-color:var(--primary);

        box-shadow:
            0 0 0 4px rgba(37,99,235,.1);
    }

    /* BUTTONS */

    .card-body button{
        height:50px;

        border:none;

        border-radius:14px;

        background:
            linear-gradient(
                135deg,
                var(--primary),
                var(--primary-light)
            );

        color:white;

        font-weight:700;

        padding:0 24px;

        transition:.3s;

        box-shadow:
            0 10px 20px rgba(37,99,235,.15);
    }

    .card-body button:hover{
        transform:translateY(-2px);
    }

    /* STATUS */

    .status-box{
        margin-top:18px;

        background:#f8fafc;

        border:1px dashed #cbd5e1;

        border-radius:16px;

        padding:18px;

        display:flex;
        align-items:center;
        gap:14px;
    }

    .status-icon{
        width:48px;
        height:48px;

        border-radius:14px;

        background:
            linear-gradient(
                135deg,
                #16a34a,
                #22c55e
            );

        color:white;

        display:flex;
        align-items:center;
        justify-content:center;

        font-size:22px;
    }

    .status-box h4{
        margin:0;
        font-size:15px;
        font-weight:800;
        color:var(--dark);
    }

    .status-box p{
        margin:4px 0 0;
        font-size:13px;
        color:var(--muted);
    }

    /* MOBILE */

    @media(max-width:900px){

        .profile-grid{
            grid-template-columns:1fr;
        }

        .card-body .grid{
            grid-template-columns:1fr !important;
        }

        .col-span-2{
            grid-column:span 1 !important;
        }
    }

    @media(max-width:600px){

        .profile-header{
            padding:30px 22px;
        }

        .profile-top{
            flex-direction:column;
            text-align:center;
        }

        .profile-avatar{
            width:85px;
            height:85px;
            font-size:30px;
        }

        .profile-info h1{
            font-size:25px;
        }

        .card-header,
        .card-body{
            padding:22px;
        }
    }

</style>

<div class="profile-wrapper">

    <!-- HEADER -->
    <div class="profile-header">

        <div class="profile-top">

            <div class="profile-avatar">
                {{ strtoupper(substr(auth()->user()->first_name ?? 'U',0,1)) }}
            </div>

            <div class="profile-info">

                <h1>
                    {{ auth()->user()->first_name }}
                    {{ auth()->user()->last_name }}
                </h1>

                <p>
                    Mfumo wa Mahudhurio ya Walimu · EduAttend
                </p>

                <div class="gov-badge">
                    🇹🇿 Chemba District Council
                </div>

            </div>

        </div>

    </div>

    <!-- GRID -->
    <div class="profile-grid">

        <!-- PROFILE INFO -->
        <div class="profile-card">

            <div class="card-header">

                <h3>
                    👤 Hariri Taarifa
                </h3>

                <p>
                    Sasisha taarifa zako za akaunti na mawasiliano.
                </p>

            </div>

            <div class="card-body">

                <livewire:profile.update-profile-information-form />

                <div class="status-box">

                    <div class="status-icon">
                        ✓
                    </div>

                    <div>

                        <h4>
                            Akaunti Imethibitishwa
                        </h4>

                        <p>
                            Mfumo wako uko salama na umeunganishwa kikamilifu.
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <!-- PASSWORD -->
        <div class="profile-card">

            <div class="card-header">

                <h3>
                    🔐 Badili Password
                </h3>

                <p>
                    Hakikisha unatumia password yenye usalama wa kutosha.
                </p>

            </div>

            <div class="card-body">

                <livewire:profile.update-password-form />

            </div>

        </div>

    </div>

</div>

@endsection