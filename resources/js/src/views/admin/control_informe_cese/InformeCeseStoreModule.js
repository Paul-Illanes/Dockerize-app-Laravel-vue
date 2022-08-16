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
    fetchInformeCese(ctx, queryParams) {
        return new Promise((resolve, reject) => {
          
          if(!JSON.parse(sessionStorage.getItem('informecese')) || queryParams.refreshStatus == 1){
            
            axios
              .get('/api/auth/informe_cese/')
              .then(response => {
                console.log(response)
                sessionStorage.setItem(
                  "informecese",
                  JSON.stringify(response.data)
              );
              
              const { q = '', perPage = 20, page = 1, sortBy = 'id', sortDesc = false, status_baja = "", type = null } = queryParams
              /* eslint-enable */
           
              const queryLowered = q.toLowerCase();
              const filteredData = response.data.filter(
                baja =>
                  /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                  (baja.dni.toLowerCase().includes(queryLowered) ||
                  baja.nombre.toLowerCase().includes(queryLowered)),
              )
 
  
              const sortedData = filteredData.sort(sortCompare(sortBy))
              
              if (sortDesc) sortedData.reverse()
              const xd = [
                {
                  invoices: paginateArray(sortedData, perPage, page),
                  total: filteredData.length,
                },
              ]
              resolve(xd)
              })
              .catch(error => reject(error))
          } else {
            const dato = JSON.parse(sessionStorage.getItem('informecese'));

            const { q = '', perPage = 20, page = 1, sortBy = 'id', sortDesc = false, status_baja = "", type = null } = queryParams
            /* eslint-enable */
            const queryLowered = q.toLowerCase();


            const filteredData = dato.filter(
              baja =>
                /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                (baja.dni.toLowerCase().includes(queryLowered) ||
                baja.nombre.toLowerCase().includes(queryLowered)),
            )
            // if(this.status_baja === 0){
            //   this.filteredData = dato.filter(
            //     baja =>
            //       /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
            //       (baja.dni.toLowerCase().includes(queryLowered) ||
            //       baja.nombres.toLowerCase().includes(queryLowered)) &&
            //       baja.status_baja === 0,
            //   )
            // }
            // else if(this.status_baja == 1 || this.status_baja == 2 || this.status_baja == 3 ){
            //   this.filteredData = dato.filter(
            //     baja =>
            //       /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
            //       (baja.dni.toLowerCase().includes(queryLowered) ||
            //       baja.nombres.toLowerCase().includes(queryLowered)) &&
            //       baja.status_baja >= 1,
            //   )
            // }

            const sortedData = filteredData.sort(sortCompare(sortBy))
            
            if (sortDesc) sortedData.reverse()
            const xd = [
              {
                invoices: paginateArray(sortedData, perPage, page),
                total: filteredData.length,
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
