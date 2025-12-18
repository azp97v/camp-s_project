<template>
  <AppLayout title="الأهداف">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        الأهداف
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-4">
          <Link href="/goals/create" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
            إنشاء هدف جديد
          </Link>
        </div>

        <div v-if="goals.length === 0" class="bg-white shadow rounded-lg p-6">
          <p class="text-gray-600">لا توجد أهداف حالياً</p>
        </div>

        <div v-else class="grid gap-6">
          <div v-for="goal in goals" :key="goal.id" class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-start">
              <div class="flex-1">
                <Link :href="`/goals/${goal.id}`" class="text-lg font-semibold text-gray-900 hover:text-blue-600">
                  {{ goal.title }}
                </Link>
                <p class="text-gray-600 mt-2">{{ goal.description }}</p>
              </div>
              <Link :href="`/goals/${goal.id}/delete`" method="delete" as="button" class="text-red-600 hover:text-red-900 ml-4">
                حذف
              </Link>
            </div>

            <div class="mt-4">
              <div class="flex justify-between text-sm text-gray-600">
                <span>الوقت المتبقي: {{ formatSeconds(goal.remaining_duration_seconds) }}</span>
                <span>إجمالي الوقت: {{ formatSeconds(goal.total_duration_seconds) }}</span>
              </div>
              <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" :style="{ width: progressPercent(goal) + '%' }"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
  goals: Array,
});

const formatSeconds = (seconds) => {
  const hours = Math.floor(seconds / 3600);
  const minutes = Math.floor((seconds % 3600) / 60);
  const secs = seconds % 60;

  if (hours > 0) {
    return `${hours}h ${minutes}m`;
  }
  return `${minutes}m ${secs}s`;
};

const progressPercent = (goal) => {
  if (goal.total_duration_seconds === 0) return 0;
  return ((goal.total_duration_seconds - goal.remaining_duration_seconds) / goal.total_duration_seconds) * 100;
};
</script>
