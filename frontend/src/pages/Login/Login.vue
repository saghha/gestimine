<template lang="html">

  <section class="login">
    <div class="wrapper d-flex login align-items-center pt-5">
      <div class="row w-100">
        <div class="col-lg-6 mx-auto">
          <div class="card shadow-lg text-left p-5">
            <div class="card-body">
              <h2>Acceso</h2>
              <h4 class="font-weight-light">Bienvenidos a GestiMine!</h4>
              <ValidationObserver v-slot="{invalid}">
                <div class="form-group">
                  <ValidationProvider rules="rut" v-slot="v">
                    <label for="exampleInputEmail1" :class="{'text-danger': v.failedRules.rut}">Rut</label>
                    <input type="" class="form-control" id="rut" @input="getRut" v-model="user.rut" aria-describedby="emailHelp" placeholder="Ingrese su Rut" v-rut:live>
                    <i v-if="v.failedRules.rut" class="text-danger">El Rut no es válido</i>
                    <i class="mdi mdi-account"></i>
                  </ValidationProvider>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Contraseña</label>
                  <input type="password" class="form-control" id="exampleInputPassword1" v-model="user.password" placeholder="Ingrese contraseña">
                  <i class="mdi mdi-eye"></i>
                </div>
                <div class="mt-5">
                  <button class="btn btn-success btn-block btn-lg font-weight-medium" @click="login">Ingresar</button>
                </div>
                <div class="mt-3 text-center">
                  <a href="#" class="auth-link text-white">¿Olvidaste tu contraseña?</a>
                </div>
              </ValidationObserver>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</template>

<script lang="js">
/* eslint-disable */
import { rutValidator, rutFilter, rutInputDirective } from 'vue-dni';
import helpers from '../../components/Helper'
export default {
  name: 'login',
  data () {
    return {
      user: {
        rut: null,
        rut_dv: null,
        password: null
      }
    }
  },
  created () {
    //extend("rut", rutValidator);
  },
  methods: {
    ...helpers,
    login: function () {
      this.$store.commit('setLoading', true)
      var data = {}
      var temp =  this.user.rut.split('-')
      data.rut = temp[0].replaceAll(/([\,.])+/g, '')
      data.rut_dv = temp[1]
      data.password = this.user.password
      this.$store.dispatch('login', data).then((response) => {
        this.$router.push('/')
        this.$store.commit('setLoading', false)
      }).catch((err) => {
        this.$store.commit('setLoading', false)
      })
    },
    getRut: function (payload) {
      console.log(payload)
    }
  }
}
</script>

<style scoped lang="scss">
  .login {
    background-image: url('/img/excavator-in-action.jpg');
    background-repeat: no-repeat;
    background-size: cover;
}
</style>
