<div class="overflow-x-auto max-h-56">
    <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-700">
        <thead class="sticky top-0 bg-slate-100 dark:bg-slate-700/50">
            <tr class="text-xs font-semibold tracking-wider uppercase text-slate-600 dark:text-slate-300">
                <th class="px-4 py-2 text-left">{{ $title }}</th>
                <th class="px-4 py-2 text-right">Inscritos</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse ($data as $item => $count)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                    <td class="px-4 py-2 font-medium text-slate-800 dark:text-slate-200">{{ $item }}</td>
                    <td class="px-4 py-2 text-right text-slate-600 dark:text-slate-300">{{ $count }}</td>
                </tr>
            @empty
                 <tr><td colspan="2" class="px-4 py-3 text-center text-slate-500">No hay datos.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
