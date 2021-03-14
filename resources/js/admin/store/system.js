import axios from 'axios';

import 'dayjs/locale/ja';
import dayjs from 'dayjs';
dayjs.locale('ja');

import { v4 as uuidv4 } from 'uuid';

import { APP_URL } from '../../config/const';
import { OK } from '../../modules/util';

const state = {
  logs: [],
  version: '',
  update: false,
};

const getters = {
  update: (state) => state.update,
};

const mutations = {
  addLogs(state, log) {
    state.logs.push(log);
  },
  setVersion(state, version) {
    state.version = version;
  },
  setUpdate(state, update) {
    state.update = update;
  },
};

const actions = {
  /**
   * @description API結果からログを作成
   * @param {*} context
   * @param {*} data
   */
  createLog(context, data) {
    context.commit('addLogs', {
      uuid: uuidv4(),
      status: data.response.status,
      message: data.message,
      data: data.response.data,
      date: dayjs().format('YYYY-MM-DD HH:mm:ss'),
    });
  },
  /**
   * @description アプリの更新を検知する
   * @param {*} context
   * @returns
   */
  async version(context) {
    const response = await axios.get(APP_URL + '/mix-manifest.json', null);
    if (response.status === OK) {
      const version = response.data['/js/admin/app.js'];

      if (context.state.version === '') {
        context.commit('setVersion', version);
      }
      context.commit('setUpdate', context.state.version !== version);
      return false;
    }

    context.dispatch(
      'system/createLog',
      { response: response, message: ERROR_MESSAGES[response.status] },
      { root: true }
    );
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
