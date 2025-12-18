<template>
  <AppLayout :title="task.title">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ task.title }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
          <p class="text-gray-600">{{ task.description }}</p>

          <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <h3 class="text-sm font-semibold text-gray-900">الحالة</h3>
              <p class="mt-2 px-3 py-1 inline-block bg-blue-100 text-blue-800 rounded">{{ task.status }}</p>
            </div>

            <div>
              <h3 class="text-sm font-semibold text-gray-900">الوقت المتتبع</h3>
              <p class="mt-2 text-lg">{{ formatSeconds(task.total_tracked_seconds) }}</p>
            </div>

            <div v-if="task.deadline">
              <h3 class="text-sm font-semibold text-gray-900">الموعد النهائي</h3>
              <p class="mt-2">{{ new Date(task.deadline).toLocaleString('ar-SA') }}</p>
            </div>

            <div>
              <h3 class="text-sm font-semibold text-gray-900">آخر جلسة</h3>
              <p class="mt-2">{{ formatSeconds(task.last_session_seconds) }}</p>
            </div>
          </div>

          <div class="mt-8 flex gap-3">
            <button
              v-if="task.status === 'idle' || task.status === 'stopped'"
              @click="startTask"
              :disabled="processing"
              class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50"
            >
              {{ task.status === 'idle' ? 'بدء' : 'استئناف' }}
            </button>

            <button
              v-if="task.status === 'running'"
              @click="stopTask"
              :disabled="processing"
              class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 disabled:opacity-50"
            >
              إيقاف مؤقت
            </button>

            <button
              v-if="task.status === 'stopped'"
              @click="finishTask"
              :disabled="processing"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
            >
              إنهاء الجلسة
            </button>

            <button
              v-if="task.status === 'running' || task.status === 'stopped'"
              @click="cancelTask"
              :disabled="processing"
              class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 disabled:opacity-50"
            >
              إلغاء
            </button>
          </div>

          <div class="mt-8">
            <Link :href="`/goals/${task.goal_id}`" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
              رجوع
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  task: Object,
});

const form = useForm({});
const processing = ref(false);

const formatSeconds = (seconds) => {
  const hours = Math.floor(seconds / 3600);
  const minutes = Math.floor((seconds % 3600) / 60);
  const secs = seconds % 60;

  if (hours > 0) {
    return `${hours}h ${minutes}m ${secs}s`;
  }
  return `${minutes}m ${secs}s`;
};

const startTask = () => {
  processing.value = true;
  form.post(`/tasks/${props.task.id}/start`, {
    onFinish: () => processing.value = false,
  });
};

const stopTask = () => {
  processing.value = true;
  form.post(`/tasks/${props.task.id}/stop`, {
    onFinish: () => processing.value = false,
  });
};

const finishTask = () => {
  processing.value = true;
  form.post(`/tasks/${props.task.id}/finish`, {
    onFinish: () => processing.value = false,
  });
};

const cancelTask = () => {
  processing.value = true;
  form.post(`/tasks/${props.task.id}/cancel`, {
    onFinish: () => processing.value = false,
  });
};
</script>
