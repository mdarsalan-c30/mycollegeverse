<x-admin-layout>
    <div class="space-y-10">
        <!-- Header -->
        <div>
            <h2 class="text-3xl font-black text-admin-secondary tracking-tight">Deep Analytics Cluster</h2>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em] mt-1 italic">Real-time pulse of the academic multiverse expansion</p>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="glass p-8 rounded-[2.5rem] border-white/50 shadow-xl shadow-slate-200/50 flex flex-col italic">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Citizens</span>
                <div class="flex items-center justify-between">
                    <span class="text-3xl font-black text-admin-secondary">{{ number_format($stats['total_users']) }}</span>
                    <span class="text-green-500 text-[10px] font-bold">+12% Flux</span>
                </div>
            </div>
            <div class="glass p-8 rounded-[2.5rem] border-white/50 shadow-xl shadow-slate-200/50 flex flex-col italic">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Knowledge Assets</span>
                <div class="flex items-center justify-between">
                    <span class="text-3xl font-black text-admin-secondary">{{ number_format($stats['total_notes']) }}</span>
                    <span class="text-admin-primary text-[10px] font-bold">+8% Growth</span>
                </div>
            </div>
            <div class="glass p-8 rounded-[2.5rem] border-white/50 shadow-xl shadow-slate-200/50 flex flex-col italic text-red-600">
                <span class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-2">Security Flags</span>
                <div class="flex items-center justify-between">
                    <span class="text-3xl font-black">{{ $stats['pending_reports'] }}</span>
                    <span class="text-[10px] font-bold">Queue Critical</span>
                </div>
            </div>
            <div class="glass p-8 rounded-[2.5rem] border-white/50 shadow-xl shadow-slate-200/50 flex flex-col italic">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Active Institutions</span>
                <div class="flex items-center justify-between">
                    <span class="text-3xl font-black text-admin-secondary">{{ $stats['active_colleges'] }}</span>
                    <span class="text-slate-300 text-[10px] font-bold">Master Nodes</span>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Growth Curve (Users & Assets) -->
            <div class="bg-white border border-admin-border rounded-[3rem] p-10 space-y-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black text-admin-secondary leading-none">Expansion Flux</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Last 30 days of growth activity</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-admin-primary"></span>
                            <span class="text-[9px] font-black uppercase text-slate-400">Citizens</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-indigo-600"></span>
                            <span class="text-[9px] font-black uppercase text-slate-400">Assets</span>
                        </div>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>

            <!-- Institutional Heatmap (Bar Chart) -->
            <div class="bg-white border border-admin-border rounded-[3rem] p-10 space-y-6 shadow-sm">
                <div>
                    <h3 class="text-xl font-black text-admin-secondary leading-none">Institutional Heatmap</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Top active nodes by citizen volume</p>
                </div>
                <div class="h-80">
                    <canvas id="heatmapChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // High-Fidelity Charting Engine 🌌
        
        // 1. Expansion Flux (Line Chart)
        const ctxGrowth = document.getElementById('growthChart').getContext('2d');
        new Chart(ctxGrowth, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [
                    {
                        label: 'Citizen Registrations',
                        data: {!! json_encode($userGrowthData) !!},
                        borderColor: '#FF4D4D',
                        backgroundColor: 'rgba(255, 77, 77, 0.05)',
                        borderWidth: 4,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Knowledge Assets',
                        data: {!! json_encode($noteFluxData) !!},
                        borderColor: '#4F46E5',
                        backgroundColor: 'rgba(79, 70, 229, 0.05)',
                        borderWidth: 4,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 9, weight: 'bold' }, color: '#94a3b8', maxRotation: 0 } },
                    y: { grid: { borderDash: [5, 5], color: '#f1f5f9' }, ticks: { font: { size: 9, weight: 'bold' }, color: '#94a3b8' } }
                }
            }
        });

        // 2. Institutional Heatmap (Bar Chart)
        const ctxHeatmap = document.getElementById('heatmapChart').getContext('2d');
        new Chart(ctxHeatmap, {
            type: 'bar',
            data: {
                labels: {!! json_encode($collegeActivity->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($collegeActivity->pluck('student_count')) !!},
                    backgroundColor: '#1E1E2E',
                    borderRadius: 12,
                    barThickness: 24,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 8, weight: 'bold' }, color: '#94a3b8' } },
                    y: { grid: { borderDash: [5, 5], color: '#f1f5f9' }, ticks: { font: { size: 9, weight: 'bold' }, color: '#94a3b8' } }
                }
            }
        });
    </script>
    @endpush
</x-admin-layout>
