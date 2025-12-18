<template>
  <AppLayout :title="goal.title">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ goal.title }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6 mb-6">
          <p class="text-gray-600">{{ goal.description }}</p>

          <div class="mt-4">
            <div class="flex justify-between text-sm text-gray-600 mb-2">
              <span>الوقت المتبقي: {{ formatSeconds(goal.remaining_duration_seconds) }}</span>
              <span>إجمالي الوقت: {{ formatSeconds(goal.total_duration_seconds) }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
              <div class="bg-green-600 h-3 rounded-full transition-all" :style="{ width: progressPercent + '%' }"></div>
            </div>
          </div>

          <div class="mt-6">
            <Link href="/goals" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
              رجوع
            </Link>
          </div>
        </div>

        <div class="mb-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">المهام</h3>

          <form @submit.prevent="addTask" class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">
                عنوان المهمة
              </label>
              <input
                v-model="newTask.title"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                required
              />
            </div>

            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">
                الوصف
              </label>
              <textarea
                v-model="newTask.description"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                rows="2"
              ></textarea>
            </div>

            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">
                الموعد النهائي (اختياري)
              </label>
              <input
                v-model="newTask.deadline"
                type="datetime-local"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <button
              type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            >
              إضافة مهمة
            </button>
          </form>
        </div>

        <div v-if="tasks.length === 0" class="text-center py-8">
          <p class="text-gray-600">لا توجد مهام</p>
        </div>

        <div v-else class="grid gap-4">
          <div v-for="task in tasks" :key="task.id" class="bg-white shadow rounded-lg p-4">
            <Link :href="`/tasks/${task.id}`" class="text-lg font-semibold text-gray-900 hover:text-blue-600">
              {{ task.title }}
            </Link>
            <p class="text-gray-600 mt-1">{{ task.description }}</p>

            <div class="mt-3 flex justify-between items-center">
              <div class="text-sm text-gray-500">
                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ task.status }}</span>
              </div>
              <div class="text-sm">
                الوقت المتتبع: {{ formatSeconds(task.total_tracked_seconds) }}
              </div>
            </div>

            <div class="mt-3 flex gap-2">
              <Link :href="`/tasks/${task.id}`" class="px-2 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                عرض
              </Link>
              <Link :href="`/tasks/${task.id}/delete`" method="delete" as="button" class="px-2 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                حذف
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
  goal: Object,
  tasks: Array,
});

const newTask = ref({
  title: '',
  description: '',
  deadline: '',
});

const taskForm = useForm({
  title: '',
  description: '',
  deadline: '',
});

const progressPercent = computed(() => {
  if (!props.goal || props.goal.total_duration_seconds === 0) return 0;
  return ((props.goal.total_duration_seconds - props.goal.remaining_duration_seconds) / props.goal.total_duration_seconds) * 100;
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

const addTask = () => {
  taskForm.post(`/goals/${props.goal.id}/tasks`, {
    onFinish: () => {
      newTask.value = { title: '', description: '', deadline: '' };
    },
  });
};
</script>
