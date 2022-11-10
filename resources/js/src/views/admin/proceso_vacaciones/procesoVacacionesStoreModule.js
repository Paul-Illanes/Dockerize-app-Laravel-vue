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
    fetchProcesoVacaciones(ctx, queryParams) {
        return new Promise((resolve, reject) => {
            axios
              .get('/api/auth/areas_vacaciones/')
              .then(response => {

              const { q = '', perPage = 20, page = 1, sortBy = 'id', sortDesc = false } = queryParams
              /* eslint-enable */
           
              const queryLowered = q.toLowerCase();
              const filteredData  = response.data.filter(
                proceso =>
                  /* eslint-disable operator-linebreak, implicit-arrow-linebreak */
                  (proceso.area.toLowerCase().includes(queryLowered) ||
                  proceso.supestructura.toLowerCase().includes(queryLowered) || proceso.dependencia.toLowerCase().includes(queryLowered)),
              )
              const sortedData = filteredData.sort(sortCompare(sortBy))
              
              if (sortDesc) sortedData.reverse()
              const data = [
                {
                  items: paginateArray(sortedData, perPage, page),
                  total: filteredData.length,
                },
              ]
              resolve(data)
              })
              .catch(error => reject(error))
        })
      },
  },
}
