import '../css/app.css';
import './bootstrap';
import 'ant-design-vue/dist/reset.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Antd from 'ant-design-vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
      resolvePageComponent(
          `./Pages/${name}.vue`,
          import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
      ),
  setup({ el, App, props, plugin }) {
      createApp({ render: () => h(App, props) })
          .use(plugin)
          .use(ZiggyVue)
          .use(Antd)
          .mount(el);
  },
  progress: {
      color: '#4B5563',
  },
});
