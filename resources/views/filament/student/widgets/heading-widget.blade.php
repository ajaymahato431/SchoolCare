<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col items-center justify-center max-w-md p-6 mx-auto bg-gray-100 rounded-lg shadow-md">
            <h1 class="mb-4 text-3xl font-bold text-gray-800">{{ $name }}</h1>
            <div class="grid w-full grid-cols-2 gap-4 text-center">

                <div class="text-lg font-medium text-gray-600">Grade: {{ $grade }}</div>

                <div class="text-lg font-medium text-gray-600">Total Attendance: {{ $total_attendance }}</div>

                <div class="text-lg font-medium text-gray-600">Present: {{ $present }}</div>

                <div class="text-lg font-medium text-gray-600">Not Present: {{ $notPresent }}</div>

                <div class="text-lg font-medium text-gray-600">Total Assignments: {{ $total_assignment }}</div>
                <div class="text-lg font-medium text-gray-600">Assignments Completed: {{ $assignment }}</div>

                <div class="text-lg font-medium text-gray-600">Assignments Not Completed: {{ $notAssignment }}</div>
            </div>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>
