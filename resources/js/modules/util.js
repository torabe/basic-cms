// status code
export const OK = 200;
export const CREATED = 201;
export const UNAUTHORIZED = 403;
export const NOT_FOUND = 404;
export const UNPROCESSABLE_ENTITY = 422;
export const INTERNAL_SERVER_ERROR = 500;

export const ERROR_MESSAGES = [];
ERROR_MESSAGES[UNAUTHORIZED] = '許可されていません';
ERROR_MESSAGES[NOT_FOUND] = 'サーバーとの通信に失敗しました';
ERROR_MESSAGES[UNPROCESSABLE_ENTITY] = '入力内容にエラーがあります';
ERROR_MESSAGES[INTERNAL_SERVER_ERROR] = 'システムエラー';

// role identifier
export const ROLE_DEVELOPER = 'developer';
export const ROLE_ADMIN = 'administrator';
export const ROLE_EDITOR = 'editor';
export const ROLE_CONTRIBUTOR = 'contributor';

/**
 * Cookieを取得する
 * @returns {Obeject} Cookie
 */
export function getCookie() {
  const object = {};

  document.cookie.split(';').forEach(cookie => {
    const [key, value] = cookie.split('=');
    object[key.trim()] = value;
  });

  return object;
}

/**
 * @description format number
 * @export
 * @param {*} $number
 * @returns
 */
export function numberFormat($number) {
  const formatter = new Intl.NumberFormat('ja-JP');
  return formatter.format($number);
}
