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
          
          if(!JSON.parse(sessionStorage.getItem('papeleta')) || queryParams.refreshStatus == 1){
            
            axios
              .get('/api/auth/papeleta/')
              .then(response => {
               
                sessionStorage.setItem(
                  "papeleta",
                  JSON.stringify(response.data.data)
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
                this.filteredData = response.data.data.filter(
                  papeleta =>
                    /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                    (papeleta.dni.toLowerCase().includes(queryLowered) ||
                    papeleta.nombres.toLowerCase().includes(queryLowered)) &&
                    papeleta.status === 0 &&
                    papeleta.tipo_permiso === (type || papeleta.tipo_permiso),
                )
              }
              else if(this.status == 1 ){
                this.filteredData = response.data.data.filter(
                  papeleta =>
                    /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                    (papeleta.dni.toLowerCase().includes(queryLowered) ||
                    papeleta.nombres.toLowerCase().includes(queryLowered)) &&
                    papeleta.status >= 1 &&
                    papeleta.tipo_permiso === (type || papeleta.tipo_permiso),
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
            const dato = JSON.parse(sessionStorage.getItem('papeleta'));

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
                papeleta =>
                  /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                  (papeleta.dni.toLowerCase().includes(queryLowered) ||
                  papeleta.nombres.toLowerCase().includes(queryLowered)) &&
                  papeleta.status === 0 &&
                  papeleta.tipo_permiso === (type || papeleta.tipo_permiso),
              )
            }
            else if(this.status == 1 || this.status == 2 || this.status == 3 ){
              this.filteredData = dato.filter(
                papeleta =>
                  /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                  (papeleta.dni.toLowerCase().includes(queryLowered) ||
                  papeleta.nombres.toLowerCase().includes(queryLowered)) &&
                  papeleta.status >= 1 &&
                  papeleta.tipo_permiso === (type || papeleta.tipo_permiso),
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
