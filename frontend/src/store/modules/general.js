const { default: Axios } = require("axios")
const state = {
    loading: false,
    user: null,
    token: null,
    logged: false,
    sideBar: [],
    profile: null,
    slug_datos_mina: null
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
    },
    setDatosMina(state, slug) {
        state.slug_datos_mina = slug
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
                    if(!!window.localStorage.getItem('slugDatosMina')) {
                        var slug = window.localStorage.getItem('slugDatosMina')
                        commit('setDatosMina', slug)
                    } else {
                        Axios.get('datos-mina/ultimo', {
                            headers: {
                                'Authorization': 'Bearer ' + state.token
                            }
                        }).then((response) => {
                            window.localStorage.setItem('slugDatosMina', response.data.slug)
                            commit('setDatosMina', response.data.slug)
                        }).catch((err) => {
                            console.log(err)
                        })
                    }
                    console.log("obtengo los datos desde el localstore")
                    resolve()
                } else {
                    console.log("reject")
                    reject(false)
                }
            } else {
                console.log("los tengo en mi store")
                if(!!window.localStorage.getItem('slugDatosMina')) {
                    var slug = window.localStorage.getItem('slugDatosMina')
                    commit('setDatosMina', slug)
                    resolve()
                } else {
                    Axios.get('datos-mina/ultimo', {
                        headers: {
                            'Authorization': 'Bearer ' + state.token
                        }
                    }).then((response) => {
                        window.localStorage.setItem('slugDatosMina', response.data.slug)
                        commit('setDatosMina', response.data.slug)
                    }).catch((err) => {
                        console.log(err)
                    }).finally(() => {
                        resolve()
                    })
                }
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
            window.localStorage.removeItem('token')
            window.localStorage.removeItem('user')
            window.localStorage.removeItem('slugDatosMina')
            console.log('eliminando vuex')
            commit('setLogout')
            commit('setLogin', false)
            commit('setDatosMina', false)
            resolve()
        })
    },
    changeDatosMina({commit}, slug) {
        commit('setDatosMina', slug)
        window.localStorage.setItem('slugDatosMina', slug)
    }
}

export default {
    state,
    mutations,
    actions
}