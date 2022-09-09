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
    fetchPersona(ctx, queryParams) {
        return new Promise((resolve, reject) => {
            axios
              .get('/api/auth/persona/')
              .then(response => {
              
              const { q = '', perPage = 20, page = 1, sortBy = 'id', sortDesc = false, status = "" } = queryParams
              /* eslint-enable */
           
              const queryLowered = q.toLowerCase();
              if (status == 'Todos'){
                this.status = 9  
              }
              if (status == 'Nuevos'){
                  this.status = 0  
              }
              if (status == 'Activos'){
                this.status = 1
              }
              if (status == 'Baja'){
                this.status = 2
              }
              const filteredData = [];
              if(this.status == 9){
                this.filteredData  = response.data.filter(
                  persona =>
                    /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                    (persona.dni.toLowerCase().includes(queryLowered) ||
                    persona.nombres.toLowerCase().includes(queryLowered)) &&
                    persona.status < this.status,
                )
              } else if(this.status < 9){
                this.filteredData  = response.data.filter(
                  persona =>
                    /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                    (persona.dni.toLowerCase().includes(queryLowered) ||
                    persona.nombres.toLowerCase().includes(queryLowered)) &&
                    persona.status === this.status,
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
