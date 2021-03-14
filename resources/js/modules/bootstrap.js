import axios from 'axios';
import { getCookie, INTERNAL_SERVER_ERROR } from './util';

// Ajaxリクエストであることを示すヘッダーを付与する
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

axios.defaults.validateStatus = status => status <= INTERNAL_SERVER_ERROR;

axios.interceptors.request.use(config => {
  const cookie = getCookie();
  if (!cookie['XSRF-TOKEN']) {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    return config;
  }

  // クッキーからトークンを取り出してヘッダーに添付する
  config.headers['X-XSRF-TOKEN'] = cookie['XSRF-TOKEN'];

  return config;
});

axios.interceptors.response.use(
  response => response,
  error => error.response || error
);

window.axios = axios;
