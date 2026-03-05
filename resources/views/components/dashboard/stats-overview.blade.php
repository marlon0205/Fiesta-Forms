<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <x-cyber.kpi-card
        label="Total Forms"
        :value="$totalForms"
        icon="description"
        gradient="from-blue-500 to-cyan-400"
    />
    <x-cyber.kpi-card
        label="Active Polls"
        :value="$activePolls"
        icon="pending_actions"
        gradient="from-emerald-500 to-teal-400"
    />
    <x-cyber.kpi-card
        label="Responses"
        :value="number_format($totalResponses)"
        icon="mark_chat_read"
        gradient="from-violet-500 to-purple-400"
    />
    <x-cyber.kpi-card
        label="Total Users"
        :value="number_format($totalUsers)"
        icon="trending_up"
        gradient="from-orange-500 to-amber-400"
    />
</div>
