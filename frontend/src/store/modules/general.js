const { default: Axios } = require("axios")
const state = {
    loading: false,
    user: null,
    token: null,
    logged: false,
    sideBar: [],
    profile: null
}

const mutations = {
    setUser(state, user) {
        state.user = user
        state.token = user.access_token
    },
    setLoading(state, busy) {
        state.loading = busy
    },
    setLogout(state) {
        state.token = null
        state.user = null
        state.sideBar = []
        state.profile = null
    },
    setLogin(state, cond) {
        state.logged = cond
    },
    setSideBar(state, side) {
        state.sideBar = side
    },
    setProfile(state, profile) {
        state.profile = profile
    }
}

const actions = {
    authenticate({ commit, state }) {
        return new Promise((resolve, reject) => {
            if(state.token == null && state.user == null){
                console.log("token: "+!!localStorage.getItem('token'))
                if(!!localStorage.getItem('token')){
                    var data = JSON.parse(localStorage.getItem('user'))
                    commit('setUser', data)
                    commit('setLogin', true)
                    console.log("obtengo los datos desde el localstore")
                    resolve()
                } else {
                    console.log("reject")
                    reject(false)
                }
            } else {
                console.log("los tengo en mi store")
                resolve()
            }
        })
    },
    login({ commit }, user) {
        return Axios.post('auth/login', user).then((response) => {
            commit('setUser', response.data)
            window.localStorage.setItem('token', response.data.access_token)
            window.localStorage.setItem('user', JSON.stringify(response.data))
        }).catch((err) => {
            console.log(err)
        })
    },
    logout({ commit }) {
        return new Promise((resolve, reject) => {
            console.log("eliminando localStore")
            localStorage.removeItem('token')
            localStorage.removeItem('user')
            console.log('eliminando vuex')
            commit('setLogout')
            commit('setLogin', false)
            resolve()
        })
    }
}

export default {
    state,
    mutations,
    actions
}