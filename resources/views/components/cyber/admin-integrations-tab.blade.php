<div class="p-8 fade-in">
    <div class="mb-8">
        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Category Integration</h3>
        <p class="text-slate-500 dark:text-slate-400">Migrate product and service categories from external sources.</p>
    </div>

    <form action="{{ route('dashboard.admin.integrations.sync') }}" method="POST" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- REST API Option -->
            <div class="glass-panel p-6 rounded-2xl border border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <span class="material-symbols-outlined">api</span>
                    </div>
                    <h4 class="font-bold text-slate-800 dark:text-white">Option 1: REST API</h4>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="api_url" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">API Endpoint URL</label>
                        <input type="url" name="api_url" id="api_url" placeholder="https://api.example.com/categories"
                            class="w-full bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all dark:text-white">
                    </div>
                    <div>
                        <label for="api_token" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Bearer Token (Optional)</label>
                        <input type="password" name="api_token" id="api_token" placeholder="Your API Token"
                            class="w-full bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all dark:text-white">
                    </div>
                </div>
            </div>

            <!-- JSON Import Option -->
            <div class="glass-panel p-6 rounded-2xl border border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <span class="material-symbols-outlined">data_object</span>
                    </div>
                    <h4 class="font-bold text-slate-800 dark:text-white">Option 2: Direct JSON</h4>
                </div>

                <div>
                    <label for="json_data" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">JSON Content</label>
                    <textarea name="json_data" id="json_data" rows="5" placeholder='{"product_categories": [{"name": "Electronics"}], "service_categories": [{"name": "Consulting"}]}'
                        class="w-full bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-mono focus:ring-2 focus:ring-indigo-500 transition-all dark:text-white"></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">sync</span> Sync Categories
            </button>
        </div>
    </form>

    <div class="mt-12 p-6 rounded-2xl bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/50">
        <div class="flex gap-4">
            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">info</span>
            <div>
                <h5 class="font-bold text-amber-800 dark:text-amber-300 mb-1">Data Format Specification</h5>
                <p class="text-sm text-amber-700/80 dark:text-amber-400/80 leading-relaxed">
                    The integration expects a JSON object with two optional arrays: <code class="bg-amber-100 dark:bg-amber-900/40 px-1.5 py-0.5 rounded text-amber-900 dark:text-amber-200">product_categories</code> and <code class="bg-amber-100 dark:bg-amber-900/40 px-1.5 py-0.5 rounded text-amber-900 dark:text-amber-200">service_categories</code>. 
                    Each item in these arrays must contain a <code class="bg-amber-100 dark:bg-amber-900/40 px-1.5 py-0.5 rounded text-amber-900 dark:text-amber-200">name</code> field. Existing categories will be skipped.
                </p>
            </div>
        </div>
    </div>
</div>
