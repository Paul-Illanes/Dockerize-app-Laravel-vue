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
    fetchInvoices(ctx, queryParams) {
        return new Promise((resolve, reject) => {
          
          if(!JSON.parse(sessionStorage.getItem('cambio_turno')) || queryParams.refreshStatus == 1){
            
            axios
              .get('/api/auth/cambios_turno/')
              .then(response => {
                
                sessionStorage.setItem(
                  "cambio_turno",
                  JSON.stringify(response.data)
              );
              
              const { q = '', perPage = 20, page = 1, sortBy = 'id', sortDesc = false, status = "", type = null } = queryParams
              /* eslint-enable */
           
              const queryLowered = q.toLowerCase();
  
              if (status == 'Pendientes'){
                  this.status = 0  
              }
              if (status == 'Procesados'){
                this.status = 1
              }
              const filteredData = []
              if(this.status === 0){
                this.filteredData = response.data.filter(
                  cambio_turno =>
                    /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                    (cambio_turno.aceptante_nombre.toLowerCase().includes(queryLowered) ||
                    cambio_turno.solicitante_nombre.toLowerCase().includes(queryLowered)) &&
                    cambio_turno.status === 0 &&
                    cambio_turno.dependencia === (type || cambio_turno.dependencia),
                )
              }
              else if(this.status == 1 ){
                this.filteredData = response.data.filter(
                  cambio_turno =>
                    /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                    (cambio_turno.aceptante_nombre.toLowerCase().includes(queryLowered) ||
                    cambio_turno.solicitante_nombre.toLowerCase().includes(queryLowered)) &&
                    cambio_turno.status >= 1 &&
                    cambio_turno.dependencia === (type || cambio_turno.dependencia),
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
              .catch(error => {
                console.log(error)
                reject(error)
              })
              
          } else {
            const dato = JSON.parse(sessionStorage.getItem('cambio_turno'));

            const { q = '', perPage = 20, page = 1, sortBy = 'id', sortDesc = false, status = "", type = null } = queryParams
            /* eslint-enable */
            const queryLowered = q.toLowerCase();

            if (status == 'Pendientes'){
                this.status = 0  
            }
            if (status == 'Procesados'){
              this.status = 1
            }
            const filteredData = []
            if(this.status === 0){
              this.filteredData = dato.filter(
                cambio_turno =>
                  /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                  (cambio_turno.aceptante_nombre.toLowerCase().includes(queryLowered) ||
                  cambio_turno.solicitante_nombre.toLowerCase().includes(queryLowered)) &&
                  cambio_turno.status === 0 &&
                  cambio_turno.dependencia === (type || cambio_turno.dependencia),
              )
            }
            else if(this.status == 1 || this.status == 2 || this.status == 3 ){
              this.filteredData = dato.filter(
                cambio_turno =>
                  /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                  (cambio_turno.aceptante_nombre.toLowerCase().includes(queryLowered) ||
                  cambio_turno.solicitante_nombre.toLowerCase().includes(queryLowered)) &&
                  cambio_turno.status >= 1 &&
                  cambio_turno.dependencia === (type || cambio_turno.dependencia),
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
