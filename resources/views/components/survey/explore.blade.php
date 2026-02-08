<div class="max-w-7xl mx-auto py-4 fade-in h-full flex flex-col">
    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-6 mb-10">
        <div>
            <h2 class="text-3xl font-black text-slate-800 dark:text-white">Explore</h2>
            <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Discover active campaigns & surveys.</p>
        </div>
        <form action="{{ request()->url() }}" method="GET" class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-pink-600 to-purple-600 rounded-xl blur opacity-25 group-hover:opacity-75 transition duration-200"></div>
                <div class="relative flex items-center bg-white dark:bg-slate-800 rounded-xl overflow-hidden">
                    <span class="material-symbols-outlined pl-4 text-slate-400">search</span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search..." class="pl-3 pr-4 py-3 bg-transparent border-none text-sm font-medium focus:ring-0 w-full sm:w-64 text-slate-700 dark:text-slate-200 placeholder-slate-400">
                </div>
            </div>
            <select name="product_category" onchange="this.form.submit()" class="px-8 py-3 border-none rounded-xl text-sm font-bold bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 shadow-lg focus:ring-0 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <option value="">All Product Categories</option>
                @foreach($productCategories as $category)
                <option value="{{ $category->name }}" {{ $selectedProductCategory == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="service_category" onchange="this.form.submit()" class="px-8 py-3 border-none rounded-xl text-sm font-bold bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 shadow-lg focus:ring-0 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <option value="">All Service Categories</option>
                @foreach($serviceCategories as $category)
                <option value="{{ $category->name }}" {{ $selectedServiceCategory == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div id="forms-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-20">
        @foreach($surveys as $form)
            <x-cyber.form-card :form="$form" />
        @endforeach
    </div>
</div>
