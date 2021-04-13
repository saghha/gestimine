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
    }
}