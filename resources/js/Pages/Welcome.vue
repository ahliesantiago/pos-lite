<template>
  <Head title='Welcome' />
  <div class='flex justify-center items-center'>
    <div class='bg-white mt-5 p-5 rounded-lg shadow-md w-2/3 text-center flex flex-col gap-3'>
      <h1 class='text-2xl font-bold'>Welcome!</h1>
      <p class='text-sm text-gray-600' v-if="!isLoggedIn">
        Please login to continue.
      </p>
      <p class='text-sm text-gray-600' v-else-if="isAdminDefaultPassword">
        Since you're logging in for the first time, please create a password for your account.
        <br />
        P.S. Your password cannot be 'admin'.
      </p>

      <form @submit.prevent='submit' class='flex flex-col gap-3' v-if="!isLoggedIn || isAdminDefaultPassword">
        <div>
          <InputLabel for='username' value='Username' />
          <TextInput
            id='username'
            type='text'
            class='mt-1 mx-auto block w-full sm:w-2/3 lg:w-1/2'
            v-model='form.username'
            required
            autofocus
          />
          <InputError class='mt-2' :message='form.errors.username' />
        </div>
        <div>
          <InputLabel for='password' value='Password' />
          <div class='relative mt-1 mx-auto w-full sm:w-2/3 lg:w-1/2'>
            <TextInput
              id='password'
              :type='showPassword ? "text" : "password"'
              class='w-full pr-10'
              v-model='form.password'
              required
              autofocus
            />
            <div class='absolute inset-y-0 right-0 pr-3 flex items-center'>
              <EyeIcon
                v-if="!showPassword"
                class='text-gray-400 h-5 w-5 cursor-pointer'
                @click='togglePasswordVisibility'
              />
              <EyeSlashIcon
                v-else
                class='text-gray-400 h-5 w-5 cursor-pointer'
                @click='togglePasswordVisibility'
              />
            </div>
          </div>
          <InputError class='mt-2' :message='form.errors.username' />
        </div>
        <div>
          <button class='bg-gray-800 text-white text-sm px-4 py-2 rounded-md'>
            {{ isAdminDefaultPassword ? 'Set Password' : 'Login' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang='ts'>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline';

const props = defineProps<{
  isLoggedIn: boolean;
  username?: string;
  isAdminDefaultPassword?: boolean;
}>();

const form = useForm({
  username: props.username || '',
  password: '',
  remember: false,
});

const showPassword = ref(false);
const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};

// TO-DO
const submit = () => {
  form.post(route('login'), {
    onFinish: () => {
      form.reset();
    },
  });
};
const resetPassword = () => {
  form.post(route('password.reset'), {
    onFinish: () => {
      form.reset();
    },
  });
};
</script>
