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
                if(localStorage.getItem('token')){
                    var data = JSON.parse(localStorage.getItem('user'))
                    commit('setUser', data)
                    commit('setLogin', true)
                    // var sideBar = JSON.parse(localStorage.getItem('sideBar'))
                    // if(!!sideBar) {
                    //     commit('setSideBar', sideBar)
                    // }
                    // var profile = JSON.parse(localStorage.getItem('profile'))
                    // if(!!profile) {
                    //     commit('setProfile', profile)
                    // }
                    console.log("obtengo los datos desde el localstore")
                    resolve()
                } else {
                    reject()
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
            localStorage.removeItem('sideBar')
            localStorage.removeItem('profile')
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