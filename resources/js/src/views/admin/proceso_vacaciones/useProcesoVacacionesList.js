import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import axios from '@axios'
import { paginateArray, sortCompare } from '@/@fake-db/utils'
// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useProcesoVacacionesList() {
  // Use toast
  const toast = useToast()

  const refProcesoVacacionesListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'index', label: '#', sortable: true },
    { key: 'supestructura', sortable: true },
    { key: 'dependencia', sortable: true },
    { key: 'dependencia_id', label: 'Cod Dependencia', sortable: true },
    { key: 'area', sortable: true },
    { key: 'periodo_vacaciones', sortable: true },
    { key: 'progreso', sortable: true },
    { key: 'status', sortable: true },
  ]
  const perPage = ref(10)
  const totalInvoices = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)

  const dataMeta = computed(() => {
    const localItemsCount = refProcesoVacacionesListTable.value ? refProcesoVacacionesListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalInvoices.value,
    }
  })

  const refetchData = () => {
    refProcesoVacacionesListTable.value.refresh()
    
  }

  watch([currentPage, perPage, searchQuery], () => {
    refetchData()
  })

  const fetchProcesoVacaciones = (ctx, callback) => {
    store
      .dispatch('app-proceso-vacaciones/fetchProcesoVacaciones', {
        q: searchQuery.value,
        perPage: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        sortDesc: isSortDirDesc.value,
      })
      .then(response => {
        const { items, total } = response[0]

        callback(items)
        totalInvoices.value = total
      })
      .catch(() => {
        toast({
          component: ToastificationContent,
          props: {
            title: "Error obteniendo datos' list",
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      })
  }

  // *===============================================---*
  // *--------- UI ---------------------------------------*
  // *===============================================---*

  const resolvePaperStatusVariant = status => {
    if (status === 0) return 'wprimary'
    if (status === 1) return 'success'
    if (status === 2) return 'secondary'
    return 'danger'
  }

  const resolveInvoiceStatusVariantAndIcon = status => {
    if (status === 0) return { variant: 'warning', label: 'observado' }
    if (status === 1) return { variant: 'success', label: 'anulado' }
    if (status === 2) return { variant: 'info', label: 'aprobado' }
    return { variant: 'secondary', icon: 'XIcon', label: 'fer' }
  }

  // const resolveClientAvatarVariant = status => {
  //   if (status === 'Partial Payment') return 'primary'
  //   if (status === 'Paid') return 'danger'
  //   if (status === 'Downloaded') return 'secondary'
  //   if (status === 'Draft') return 'warning'
  //   if (status === 'Sent') return 'info'
  //   if (status === 'Past Due') return 'success'
  //   return 'primary'
  // }

  return {
    fetchProcesoVacaciones,
    tableColumns,
    perPage,
    currentPage,
    totalInvoices,
    dataMeta,
    perPageOptions,
    searchQuery,
    sortBy,
    isSortDirDesc,
    refProcesoVacacionesListTable,

    resolveInvoiceStatusVariantAndIcon,
    resolvePaperStatusVariant,
    refetchData,
  }
}
