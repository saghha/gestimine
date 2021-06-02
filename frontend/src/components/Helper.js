import _ from 'lodash'
export default {
    calcularDigitoVerificador (rut) {
        var rut_inverso = String(rut).split("").reverse()
        var digitos = [2,3,4,5,6,7]
        var suma = 0
        _.forEach(rut_inverso, (value, index) => {
            var index_digitos
            if(index  >= digitos.length) {
                index_digitos = index - digitos.length
            } else {
                index_digitos = index
            }
            suma += Number(value)*digitos[index_digitos]
        })
        var resto = suma % 11
        if(resto === 0) {
            return 0
        } else if (resto == 1) {
            return 'k'
        } else {
            return 11 - resto
        }
    },
    showToast (infoToast) {
        this.$swal({
            toast: true,
            icon: infoToast.icon,
            title: infoToast.title,
            position: 'top-end',
            timer: 3000,
            showConfirmButton: false,
            timerProgressBar: true,
            onOpen: (toast) => {
                toast.addEventListener('mouseenter', this.$swal.stopTimer)
                toast.addEventListener('mouseleave', this.$swal.resumeTimer)
            }
        })
    },
    showAlert (infoAlert) {
        this.$swal({
            icon: infoAlert.icon,
            title: infoAlert.title,
            confirmButtonText: 'Ok'
        })
    },
    showInfoAlert (info) {
        this.$swal({
            title: info.title,
            icon: info.icon,
            text: info.text,
            showCancelButton: false,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Ok",
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: true
        })
    },
}