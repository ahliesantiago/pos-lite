<script setup lang="ts">
import { ref, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { EyeIcon, EyeSlashIcon } from "@heroicons/vue/24/outline";
import GuestLayout from "@/Layouts/GuestLayout.vue";

const props = defineProps<{
  isLoggedIn: boolean;
  username?: string;
  isPasswordDefault?: boolean;
  isResettingPassword?: boolean;
}>();

const form = useForm({
  username: props.username || "",
  password: "",
  remember: false,
});

const model = defineModel<string>({ required: true });

const input = ref<HTMLInputElement | null>(null);

const showPassword = ref(false);
const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};

const submit = () => {
  if (props.isPasswordDefault || props.isResettingPassword) {
    resetPassword();
  } else {
    login();
  }
};

const login = () => {
  form.post(route("login"), {
    onFinish: () => {
      form.reset();
    },
  });
};

const resetPassword = () => {
  form.post(route("password.reset"), {
    onFinish: () => {
      form.reset();
    },
  });
};
</script>

<template>
  <Head title="Login" />
  <GuestLayout>
    <div class="flex justify-center items-center">
      <div class="bg-white mt-5 p-5 rounded-lg shadow-md w-2/3 text-center flex flex-col gap-3">
        <h1 class="text-2xl font-bold">Welcome!</h1>
        <p class="text-sm text-gray-600" v-if="!isLoggedIn">
          Please login to continue.
        </p>
        <p class="text-sm text-gray-600" v-else-if="isPasswordDefault">
          Since you"re logging in for the first time, please create a password for your account.
          <br />
          P.S. Your password cannot be "password".
        </p>

        <form @submit.prevent="submit" class="flex flex-col gap-3" v-if="!isLoggedIn || isPasswordDefault || isResettingPassword">
          <div>
            <label for="username" class="block text-sm font-medium text-gray-600">Username</label>
            <input
              id="username"
              type="text"
              class="mt-1 p-2 mx-auto block w-full sm:w-2/3 lg:w-1/2 rounded-md border border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
              v-model="form.username"
              ref="input"
              required
              autofocus
            />
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ form.errors.username }}
            </p>
          </div>
          <div>
            <label for="password" class="block text-sm font-medium text-gray-600">{{ (isPasswordDefault || isResettingPassword) ? 'New ' : '' }}Password</label>
            <div class="relative mt-1 mx-auto w-full sm:w-2/3 lg:w-1/2">
              <input
                id="password"
                :type="showPassword ? 'text' : 'password'"
                class="w-full p-2 pr-10 rounded-md border border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                v-model="form.password"
                ref="input"
                required
                autofocus
              />
              <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <EyeIcon
                  v-if="!showPassword"
                  class="text-gray-400 h-5 w-5 cursor-pointer"
                  @click="togglePasswordVisibility"
                />
                <EyeSlashIcon
                  v-else
                  class="text-gray-400 h-5 w-5 cursor-pointer"
                  @click="togglePasswordVisibility"
                />
              </div>
            </div>
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ form.errors.username }}
            </p>
          </div>
          <div>
            <button class="bg-gray-800 text-white text-sm px-4 py-2 rounded-md">
              {{ isPasswordDefault ? "Set Password" : "Login" }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </GuestLayout>
</template>
