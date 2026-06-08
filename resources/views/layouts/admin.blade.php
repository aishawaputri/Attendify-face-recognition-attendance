<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">
    <div class="flex">
        <div class="w-64 min-h-screen bg-slate-900 text-white p-6">
            <h2 class="text-2xl font-bold mb-8">Admin Panel</h2>
            <nav class="space-y-4">
                <a href="#" class="block text-slate-300">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-400">Logout</button>
                </form>
            </nav>
        </div>

        <div class="flex-1 p-10">
            <header class="mb-10">
                <h1 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->nama }}</h1>
            </header>

            @yield('content') </div>
    </div>
</body>
</html>