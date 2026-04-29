@extends('layouts.cyber')

@section('title', 'Profile')

@section('content')
@php
    $badges = ['Early Adopter', 'Top Voter', 'Feedback Guru'];
    $points = 1250;
    $canEditProfile = $canEditProfile ?? auth()->id() === $user->user_id;
    $votesCount = $user->votes_count ?? $user->votes()->count();
    $lastActivity = $lastActivityAt
        ? \Carbon\Carbon::createFromTimestamp($lastActivityAt)
        : null;
    $roleNames = $user->roles?->pluck('name')->join(', ');
@endphp

<div class="max-w-4xl mx-auto py-8 fade-in">
    @if (session('status') === 'profile-updated')
        <div class="mb-4 p-4 bg-green-500/10 border border-green-500/50 text-green-500 rounded-xl text-sm font-bold">
            Profile updated successfully.
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="mb-4 p-4 bg-green-500/10 border border-green-500/50 text-green-500 rounded-xl text-sm font-bold">
            Password updated successfully.
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 bg-rose-500/10 border border-rose-500/50 text-rose-500 rounded-xl text-sm font-bold">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="glass-panel dark:bg-slate-800/60 rounded-[2rem] shadow-2xl overflow-hidden border-0">
        <!-- Banner -->
        <div class="h-40 bg-gradient-to-r from-violet-600 via-fuchsia-600 to-pink-600 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
            <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-white/20 rounded-full blur-3xl"></div>
        </div>

        <div class="px-8 md:px-12 pb-12 relative">
            <div class="flex justify-between items-end -mt-16 mb-8">
                <div class="flex items-end gap-6">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-tr from-yellow-400 to-pink-500 rounded-full blur opacity-75 group-hover:opacity-100 transition duration-500"></div>
                        <div class="relative w-32 h-32 rounded-full border-4 border-white dark:border-slate-800 shadow-xl bg-indigo-600 text-white flex items-center justify-center font-black text-4xl">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                    </div>
                    <div class="mb-2">
                        <h1 class="text-3xl font-black text-slate-900 dark:text-white">{{ $user->name }}</h1>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                    </div>
                </div>
                @if($canEditProfile)
                    <div class="hidden sm:flex gap-3">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="glass-button bg-rose-500 dark:bg-rose-600/50 hover:bg-rose-500 dark:hover:bg-rose-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all">Logout</button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="col-span-2 space-y-8">
                    <!-- Level Card -->
                    <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-700 dark:to-slate-800 rounded-2xl p-6 border border-white/50 dark:border-slate-600 shadow-inner">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <span class="text-xs font-black uppercase tracking-widest text-indigo-500">Current Level</span>
                                <div class="text-xl font-bold text-slate-800 dark:text-white">Master Contributor</div>
                            </div>
                            <div class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-pink-500">{{ $points }} PTS</div>
                        </div>
                        <div class="w-full bg-slate-200 dark:bg-slate-900 rounded-full h-4 mb-6 shadow-inner overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 h-4 rounded-full animate-pulse-slow" style="width: 70%"></div>
                        </div>
                        <div class="flex gap-3 flex-wrap">
                            @foreach($badges as $badge)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-700 shadow-sm text-xs font-bold text-slate-700 dark:text-slate-300">
                                <span class="material-symbols-outlined text-sm text-yellow-500">verified</span> {{ $badge }}
                            </span>
                            @endforeach
                        </div>
                    </div>

                    @if($canEditProfile)
                        <div>
                            <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4">Account Settings</h3>
                            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                                @csrf
                                @method('patch')
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Display Name</label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-shadow text-slate-700 dark:text-slate-200">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Email Address</label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-shadow text-slate-700 dark:text-slate-200">
                                    </div>
                                </div>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl text-sm font-bold transition-all">Save Changes</button>
                            </form>

                            <hr class="my-8 border-slate-200 dark:border-slate-700">

                            <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4">Update Password</h3>
                            <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                                @csrf
                                @method('put')
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Current Password</label>
                                    <input type="password" name="current_password" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-shadow text-slate-700 dark:text-slate-200">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">New Password</label>
                                        <input type="password" name="password" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-shadow text-slate-700 dark:text-slate-200">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Confirm Password</label>
                                        <input type="password" name="password_confirmation" required class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-shadow text-slate-700 dark:text-slate-200">
                                    </div>
                                </div>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl text-sm font-bold transition-all">Update Password</button>
                            </form>
                        </div>
                    @else
                        <div>
                            <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4">Profile Details</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Username</div>
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $user->name }}</div>
                                </div>
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Email Address</div>
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $user->email }}</div>
                                </div>
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Role</div>
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $roleNames ?: 'No role' }}</div>
                                </div>
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status</div>
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $user->is_active ? 'Active' : 'Inactive' }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="glass-card bg-indigo-500/10 dark:bg-indigo-500/20 border-indigo-200 dark:border-indigo-800 rounded-2xl p-6 text-center">
                        <div class="text-5xl font-black text-indigo-600 dark:text-indigo-400 mb-2">{{ $votesCount }}</div>
                        <div class="text-xs font-bold text-indigo-400 dark:text-indigo-300 uppercase tracking-widest">Surveys Taken</div>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                        <p class="text-xs text-slate-400 font-bold uppercase mb-4">Membership</p>
                        <div class="text-sm text-slate-600 dark:text-slate-300 space-y-2 font-medium">
                            <p>Joined {{ $user->created_at->format('M Y') }}</p>
                            <p>Last active: {{ $lastActivity ? $lastActivity->diffForHumans() : 'No recent activity' }}</p>
                        </div>
                        @if($canEditProfile)
                            <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account?');">
                                @csrf
                                @method('delete')
                                <button type="submit" class="mt-6 w-full py-2 text-xs font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-colors">Delete Account</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
