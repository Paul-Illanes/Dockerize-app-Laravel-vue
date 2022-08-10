import axios from '@axios'
import { paginateArray, sortCompare } from '@/@fake-db/utils'
export default {
  namespaced: true,
  state: {
  
  },
  getters: {

  },
  mutations: {},
  actions: {
    fetchBajas(ctx, queryParams) {
        return new Promise((resolve, reject) => {
          
          if(!JSON.parse(sessionStorage.getItem('bajapersonal')) || queryParams.refreshStatus == 1){
            
            axios
              .get('/api/auth/personal_bajas/')
              .then(response => {
 console.log(response.data)
                sessionStorage.setItem(
                  "bajapersonal",
                  JSON.stringify(response.data)
              );
              
              const { q = '', perPage = 20, page = 1, sortBy = 'id', sortDesc = false, status_baja = "", type = null } = queryParams
              /* eslint-enable */
           
              const queryLowered = q.toLowerCase();
  
              if (status_baja == 'Pendientes'){
                  this.status_baja = 0  
              }
              if (status_baja == 'Procesados'){
                this.status_baja = 1
              }
              const filteredData = []
              if(this.status_baja === 0){
                this.filteredData = response.data.filter(
                  baja =>
                    /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                    (baja.dni.toLowerCase().includes(queryLowered) ||
                    baja.nombres.toLowerCase().includes(queryLowered)) &&
                    baja.status_baja === 0 &&
                    baja.tipo_permiso === (type || baja.tipo_permiso),
                )
              }
              else if(this.status_baja == 1 ){
                this.filteredData = response.data.filter(
                  baja =>
                    /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                    (baja.dni.toLowerCase().includes(queryLowered) ||
                    baja.nombres.toLowerCase().includes(queryLowered)) &&
                    baja.status_baja >= 1 &&
                    baja.tipo_permiso === (type || baja.tipo_permiso),
                )
              }
  
              const sortedData = this.filteredData.sort(sortCompare(sortBy))
              
              if (sortDesc) sortedData.reverse()
              const xd = [
                {
                  invoices: paginateArray(sortedData, perPage, page),
                  total: this.filteredData.length,
                },
              ]
              resolve(xd)
              })
              .catch(error => reject(error))
          } else {
            const dato = JSON.parse(sessionStorage.getItem('bajapersonal'));

            const { q = '', perPage = 20, page = 1, sortBy = 'id', sortDesc = false, status_baja = "", type = null } = queryParams
            /* eslint-enable */
            const queryLowered = q.toLowerCase();

            if (status_baja == 'Pendientes'){
                this.status_baja = 0  
            }
            if (status_baja == 'Procesados'){
              this.status_baja = 1
            }
            const filteredData = []
            if(this.status_baja === 0){
              this.filteredData = dato.filter(
                baja =>
                  /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                  (baja.dni.toLowerCase().includes(queryLowered) ||
                  baja.nombres.toLowerCase().includes(queryLowered)) &&
                  baja.status_baja === 0,
              )
            }
            else if(this.status_baja == 1 || this.status_baja == 2 || this.status_baja == 3 ){
              this.filteredData = dato.filter(
                baja =>
                  /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                  (baja.dni.toLowerCase().includes(queryLowered) ||
                  baja.nombres.toLowerCase().includes(queryLowered)) &&
                  baja.status_baja >= 1,
              )
            }

            const sortedData = this.filteredData.sort(sortCompare(sortBy))
            
            if (sortDesc) sortedData.reverse()
            const xd = [
              {
                invoices: paginateArray(sortedData, perPage, page),
                total: this.filteredData.length,
              },
            ]
            resolve(xd)
          }
        })
      },
    transformStatus(dato) {
      if (dato === 'Pendientes') return '0'
      if (dato === 'Aprobados') return '1'
      },
    fetchInvoice(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/apps/invoice/invoices/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchClients() {
      return new Promise((resolve, reject) => {
        axios
          .get('/apps/invoice/clients')
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    // addUser(ctx, userData) {
    //   return new Promise((resolve, reject) => {
    //     axios
    //       .post('/apps/user/users', { user: userData })
    //       .then(response => resolve(response))
    //       .catch(error => reject(error))
    //   })
    // },
  },
}
