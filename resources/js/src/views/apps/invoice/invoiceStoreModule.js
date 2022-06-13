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
          if(!JSON.parse(sessionStorage.getItem('papeleta'))){
            console.log('no existe')
            axios
              .get('/api/auth/papeleta/')
              .then(response => {
                console.log(response)
                sessionStorage.setItem(
                  "papeleta",
                  JSON.stringify(response.data.data)
              );
                const { q = '', perPage = 20, page = 1, sortBy = 'id', sortDesc = false, status = null } = queryParams
                /* eslint-enable */
              
                const queryLowered = q.toLowerCase();
                const filteredData = response.data.data.filter(
                  invoice =>
                    /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                    (invoice.dni.toLowerCase().includes(queryLowered) ||
                      invoice.anio.toLowerCase().includes(queryLowered)) &&
                      invoice.status === (status || invoice.status),
                    
                )
                console.log(filteredData)
              
                /* eslint-enable  */
              
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
            console.log('si existe')
            const dato = JSON.parse(sessionStorage.getItem('papeleta'));

            const { q = '', perPage = 20, page = 1, sortBy = 'id', sortDesc = false, status = null } = queryParams
            /* eslint-enable */
            
            const queryLowered = q.toLowerCase();
            
            const filteredData = dato.filter(
              invoice =>
                /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                (invoice.dni.toLowerCase().includes(queryLowered) ||
                invoice.anio.toLowerCase().includes(queryLowered)) &&
                invoice.status === (status || invoice.status),
                
            )
            console.log(filteredData)
            /* eslint-enable  */
          
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
