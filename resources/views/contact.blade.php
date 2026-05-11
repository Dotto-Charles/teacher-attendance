{{-- resources/views/contact.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Smart Recruitment System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <style>
        body{
            font-family: 'Figtree', sans-serif;
        }

        .glass{
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800">

    {{-- NAVBAR --}}
    <header class="bg-slate-950 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between h-20">

                <a href="/" class="flex items-center gap-3">

                    <div class="w-11 h-11 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-xl">
                        S
                    </div>

                    <div>
                        <h1 class="text-white font-bold text-lg">
                            Smart Recruitment
                        </h1>

                        <p class="text-slate-400 text-sm">
                            Management System
                        </p>
                    </div>

                </a>

                <nav class="hidden md:flex items-center gap-8">

                    <a href="/" class="text-slate-300 hover:text-white transition">
                        Home
                    </a>

                    <a href="#contact" class="text-slate-300 hover:text-white transition">
                        Contact
                    </a>

                    <a href="#founder" class="text-slate-300 hover:text-white transition">
                        Founder
                    </a>

                    <a href="/login"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl transition font-semibold">
                        Login
                    </a>

                </nav>

            </div>

        </div>
    </header>

    {{-- HERO --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 text-white">

        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 left-0 w-96 h-96 bg-blue-500 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-cyan-500 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">

            <div class="text-center">

                <span class="bg-blue-600/20 border border-blue-500/30 px-5 py-2 rounded-full text-sm">
                    CONTACT SUPPORT
                </span>

                <h1 class="mt-8 text-5xl md:text-6xl font-black leading-tight">
                    Get In Touch
                </h1>

                <p class="mt-6 text-slate-300 text-lg max-w-3xl mx-auto leading-relaxed">
                    We are ready to help you with system support,
                    technical assistance and any inquiry related to the
                    Smart Recruitment Management System.
                </p>

            </div>

        </div>

    </section>

    {{-- CONTACT SECTION --}}
    <section id="contact" class="py-20">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                {{-- LEFT --}}
                <div class="bg-white rounded-3xl shadow-xl p-8 lg:p-10 border border-slate-200">

                    <span class="text-blue-700 font-semibold uppercase tracking-wider text-sm">
                        CONTACT DETAILS
                    </span>

                    <h2 class="mt-4 text-4xl font-black text-slate-900">
                        Reach Our Team
                    </h2>

                    <p class="mt-5 text-slate-600 leading-relaxed">
                        Need help with the system or account assistance?
                        Contact us anytime through the information below.
                    </p>

                    <div class="mt-10 space-y-8">

                        {{-- EMAIL --}}
                        <div class="flex gap-5">

                            <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-2xl">
                                📧
                            </div>

                            <div>
                                <h3 class="font-bold text-lg text-slate-900">
                                    Email Address
                                </h3>

                                <p class="text-slate-600 mt-1">
                                    support@smartsystem.co.tz
                                </p>
                            </div>

                        </div>

                        {{-- PHONE --}}
                        <div class="flex gap-5">

                            <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center text-2xl">
                                📞
                            </div>

                            <div>
                                <h3 class="font-bold text-lg text-slate-900">
                                    Phone Number
                                </h3>

                                <p class="text-slate-600 mt-1">
                                    +255 712 345 678
                                </p>
                            </div>

                        </div>

                        {{-- LOCATION --}}
                        <div class="flex gap-5">

                            <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center text-2xl">
                                📍
                            </div>

                            <div>
                                <h3 class="font-bold text-lg text-slate-900">
                                    Office Location
                                </h3>

                                <p class="text-slate-600 mt-1">
                                    Dar es Salaam, Tanzania
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="bg-white rounded-3xl shadow-xl p-8 lg:p-10 border border-slate-200">

                    <span class="text-blue-700 font-semibold uppercase tracking-wider text-sm">
                        SEND MESSAGE
                    </span>

                    <h2 class="mt-4 text-4xl font-black text-slate-900">
                        Contact Form
                    </h2>

                    <form class="mt-10 space-y-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>
                                <label class="block mb-2 font-semibold">
                                    Full Name
                                </label>

                                <input type="text"
                                    class="w-full rounded-2xl border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 px-5 py-4 outline-none"
                                    placeholder="Enter full name">
                            </div>

                            <div>
                                <label class="block mb-2 font-semibold">
                                    Phone Number
                                </label>

                                <input type="text"
                                    class="w-full rounded-2xl border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 px-5 py-4 outline-none"
                                    placeholder="+255 xxx xxx xxx">
                            </div>

                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">
                                Email Address
                            </label>

                            <input type="email"
                                class="w-full rounded-2xl border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 px-5 py-4 outline-none"
                                placeholder="example@email.com">
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">
                                Subject
                            </label>

                            <input type="text"
                                class="w-full rounded-2xl border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 px-5 py-4 outline-none"
                                placeholder="Enter subject">
                        </div>

                        <div>
                            <label class="block mb-2 font-semibold">
                                Message
                            </label>

                            <textarea rows="6"
                                class="w-full rounded-2xl border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 px-5 py-4 outline-none"
                                placeholder="Write your message here..."></textarea>
                        </div>

                        <button
                            class="w-full bg-blue-700 hover:bg-blue-800 transition text-white py-4 rounded-2xl font-bold text-lg shadow-lg">
                            Send Message
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </section>

    {{-- FOUNDER --}}
    <section id="founder"
        class="py-24 bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 text-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                {{-- IMAGE --}}
                <div class="flex justify-center">

                    <div class="relative">

                        <div class="absolute inset-0 bg-blue-500 blur-3xl opacity-30 rounded-full"></div>

                        <img
                            src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=987&auto=format&fit=crop"
                            class="relative w-80 h-80 object-cover rounded-3xl border-4 border-white shadow-2xl">

                    </div>

                </div>

                {{-- CONTENT --}}
                <div>

                    <span class="bg-blue-600/20 border border-blue-500/30 px-5 py-2 rounded-full text-sm">
                        SYSTEM FOUNDER
                    </span>

                    <h2 class="mt-6 text-5xl font-black">
                        Eng. John Michael
                    </h2>

                    <p class="mt-4 text-blue-200 text-xl">
                        Founder & Lead Software Engineer
                    </p>

                    <div class="mt-8 space-y-5 text-slate-300 leading-relaxed text-lg">

                        <p>
                            Professional Full Stack Developer specialized in Laravel,
                            enterprise systems, cybersecurity and scalable web applications.
                        </p>

                        <p>
                            Holds Bachelor's Degree in Computer Science from
                            University of Dar es Salaam.
                        </p>

                        <p>
                            Experienced in building government and private digital platforms
                            across Tanzania.
                        </p>

                    </div>

                    {{-- EDUCATION --}}
                    <div class="mt-10 space-y-5">

                        <div class="glass rounded-2xl p-6 border border-white/10">
                            <h3 class="font-bold text-xl">
                                Bachelor of Computer Science
                            </h3>

                            <p class="text-slate-300 mt-2">
                                University of Dar es Salaam
                            </p>
                        </div>

                        <div class="glass rounded-2xl p-6 border border-white/10">
                            <h3 class="font-bold text-xl">
                                Certified Laravel Developer
                            </h3>

                            <p class="text-slate-300 mt-2">
                                Advanced Enterprise Web Development
                            </p>
                        </div>

                    </div>

                    {{-- BUTTONS --}}
                    <div class="mt-10 flex flex-wrap gap-4">

                        <a href="#"
                            class="bg-green-500 hover:bg-green-600 transition px-6 py-3 rounded-2xl font-semibold">
                            WhatsApp
                        </a>

                        <a href="#"
                            class="bg-blue-600 hover:bg-blue-700 transition px-6 py-3 rounded-2xl font-semibold">
                            LinkedIn
                        </a>

                        <a href="#"
                            class="bg-slate-700 hover:bg-slate-600 transition px-6 py-3 rounded-2xl font-semibold">
                            GitHub
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- FOOTER --}}
    <footer class="bg-black text-slate-400 py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row items-center justify-between gap-4">

                <p class="text-sm">
                    © {{ date('Y') }} Smart Recruitment System.
                    All rights reserved.
                </p>

                <div class="flex items-center gap-6">

                    <a href="/" class="hover:text-white transition">
                        Home
                    </a>

                    <a href="#contact" class="hover:text-white transition">
                        Contact
                    </a>

                    <a href="#founder" class="hover:text-white transition">
                        Founder
                    </a>

                </div>

            </div>

        </div>

    </footer>

</body>
</html>