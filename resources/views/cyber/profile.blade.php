@extends('layouts.cyber')

@section('title', 'Profile')

@section('content')
@php
// Mock user data
$user = auth()->user() ?? (object)[
    'name' => 'Alex Jensen',
    'email' => 'alex.jensen@client.com',
    'role' => 'Customer'
];

$badges = ['Early Adopter', 'Top Voter', 'Feedback Guru'];
$points = 1250;
$votes = 42;
@endphp

<div class="max-w-4xl mx-auto py-8 fade-in">
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
                <div class="hidden sm:flex gap-3">
                    <button onclick="showToast('Edit profile feature coming soon')" class="glass-button bg-white/50 dark:bg-slate-700/50 hover:bg-white dark:hover:bg-slate-700 text-slate-800 dark:text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all">Edit Profile</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="glass-button bg-rose-500 dark:bg-rose-600/50 hover:bg-rose-500 dark:hover:bg-rose-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-all">Logout</button>
                    </form>
                </div>
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

                    <div>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4">Account</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Display Name</label>
                                <input type="text" value="{{ $user->name }}" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-shadow text-slate-700 dark:text-slate-200">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Password</label>
                                <button onclick="showToast('Password change feature coming soon')" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-500 rounded-xl px-4 py-3 text-sm flex justify-between items-center hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                    <span>••••••••••••</span>
                                    <span class="text-xs font-bold text-indigo-500">Update</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="glass-card bg-indigo-500/10 dark:bg-indigo-500/20 border-indigo-200 dark:border-indigo-800 rounded-2xl p-6 text-center">
                        <div class="text-5xl font-black text-indigo-600 dark:text-indigo-400 mb-2">{{ $votes }}</div>
                        <div class="text-xs font-bold text-indigo-400 dark:text-indigo-300 uppercase tracking-widest">Surveys Taken</div>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                        <p class="text-xs text-slate-400 font-bold uppercase mb-4">Membership</p>
                        <div class="text-sm text-slate-600 dark:text-slate-300 space-y-2 font-medium">
                            <p>Joined Oct 2024</p>
                            <p>Last active: Now</p>
                        </div>
                        <button onclick="showToast('Account deletion requires admin approval')" class="mt-6 w-full py-2 text-xs font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-colors">Delete Account</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

