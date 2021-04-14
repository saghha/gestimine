<template>
  
</template>
<script>
export default {
  name: 'EditDatosMina',
  props: {
    showModal: {
      type: Boolean,
      default: false
    },
    info: {
      type: Object,
      default: {}
    }
  },
  data () {
    return {
      datos : {}
    }
  },
  created () {
    this.datos = JSON.parse(JSON.stringify(this.info))
  },
  methods: {
    
  }
}
</script>