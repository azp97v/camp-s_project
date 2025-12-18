<template>
  <AppLayout title="إنشاء هدف جديد">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        إنشاء هدف جديد
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
          <form @submit.prevent="submit" class="p-6">
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">
                عنوان الهدف
              </label>
              <input
                v-model="form.title"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                required
              />
              <InputError :message="errors.title" class="mt-2" />
            </div>

            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">
                الوصف
              </label>
              <textarea
                v-model="form.description"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                rows="4"
              ></textarea>
              <InputError :message="errors.description" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">
                  المدة
                </label>
                <input
                  v-model="form.total_duration_input"
                  type="number"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  required
                  min="0"
                />
                <InputError :message="errors.total_duration_input" class="mt-2" />
              </div>

              <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">
                  الوحدة
                </label>
                <select
                  v-model="form.total_unit"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="seconds">ثواني</option>
                  <option value="minutes">دقائق</option>
                  <option value="hours">ساعات</option>
                </select>
                <InputError :message="errors.total_unit" class="mt-2" />
              </div>
            </div>

            <div class="flex justify-between">
              <button
                type="submit"
                :disabled="processing"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
              >
                إنشاء الهدف
              </button>
              <Link href="/goals" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                إلغاء
              </Link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { Link } from '@inertiajs/vue3';

const form = useForm({
  title: '',
  description: '',
  total_duration_input: '',
  total_unit: 'minutes',
});

const submit = () => {
  form.post('/goals', {
    onFinish: () => form.reset(),
  });
};

const errors = form.errors;
const processing = form.processing;
</script>
