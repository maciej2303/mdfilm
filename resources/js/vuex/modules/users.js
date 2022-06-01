import axios from "axios";

const state = {
    users: [],
    innerUsers: [],
    user: null,
}
const getters = {
    getUsers: (state) => state.users,
    getInnerUsers: (state) => state.innerUsers,
    getUser: (state) => {
        if (state.user && Object.keys(state.user).length === 0 && state.user.constructor === Object)
            return null

        return state.user
    },
}
const mutations = {
    setUsers(state, payload) {
        state.users = [...payload];
    },
    setInnerUsers(state, payload) {
        state.innerUsers = [...payload];
    },
    setUser(state, payload) {
        state.user = { ...payload };
    },
}
const actions = {
    async fetchUsers({ commit }, { modelId, modelClass }) {
      const {data} = await axios
        .post("/api/get/users", {
           modelId, modelClass,
        });
        commit('setUsers', data.users);
        commit('setInnerUsers', data.innerUsers)
    },
    initUsers({ commit }, payload) {
        commit('setUsers', payload);
    },
    initUser({ commit }, payload) {
        commit('setUser', payload);
    },
}
export default {
    namespaced: true,
    state, getters, mutations, actions,
}