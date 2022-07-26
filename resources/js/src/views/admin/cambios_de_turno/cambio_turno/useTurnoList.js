import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import axios from '@axios'
import { paginateArray, sortCompare } from '@/@fake-db/utils'
// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useTurnoList() {
  // Use toast
  const toast = useToast()

  const refTurnoListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'index', label: '#', sortable: true },
    { key: 'dependencia', sortable: true },
    { key: 'fecha', sortable: true },
    { key: 'solicitante_nombre', label:'Solicitante', sortable: true },
    { key: 'turno_cambio', label:'Fecha hora - turno solicitante' },
    { key: 'aceptante_nombre', label:'Aceptante', sortable: true },
    { key: 'turno_origen', label:'Fecha hora - turno aceptante' },
    { key: 'status' },
    { key: 'actions' },
  ]
  const perPage = ref(10)
  const totalInvoices = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)
  const statusFilter = ref('Pendientes')
  const typeFilter = ref(null)
  const refreshStatus = ref(0)

  const dataMeta = computed(() => {
    const localItemsCount = refTurnoListTable.value ? refTurnoListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalInvoices.value,
    }
  })

  const refetchData = () => {
    refTurnoListTable.value.refresh()
    
  }

  watch([currentPage, perPage, searchQuery, statusFilter, typeFilter,refreshStatus], () => {
    refetchData()
  })

  const fetchInvoices = (ctx, callback) => {
    store
      .dispatch('app-turno/fetchInvoices', {
        q: searchQuery.value,
        perPage: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        sortDesc: isSortDirDesc.value,
        status: statusFilter.value,
        type: typeFilter.value,
        refreshStatus: refreshStatus.value,
      })
      .then(response => {
        const { invoices, total } = response[0]

        callback(invoices)
        totalInvoices.value = total
        refreshStatus.value = 0
      })
      .catch((error) => {
        console.log(error)
        toast({
          component: ToastificationContent,
          props: {
            title: "Error fetching invoices' list",
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
    if (status === 0) return 'warning'
    if (status === 1) return 'success'
    if (status === 2) return 'danger'
    if (status === 3) return 'secondary'
    return 'danger'
  }

  const resolveInvoiceStatusVariantAndIcon = status => {
    if (status === 0) return { variant: 'warning', label: 'observado' }
    if (status === 1) return { variant: 'success', label: 'anulado' }
    if (status === 2) return { variant: 'info', label: 'aprobado' }
    return { variant: 'secondary', icon: 'XIcon', label: 'fer' }
  }

  const resolveClientAvatarVariant = status => {
    if (status === 'Partial Payment') return 'primary'
    if (status === 'Paid') return 'danger'
    if (status === 'Downloaded') return 'secondary'
    if (status === 'Draft') return 'warning'
    if (status === 'Sent') return 'info'
    if (status === 'Past Due') return 'success'
    return 'primary'
  }

  return {
    fetchInvoices,
    tableColumns,
    perPage,
    currentPage,
    totalInvoices,
    dataMeta,
    perPageOptions,
    searchQuery,
    sortBy,
    isSortDirDesc,
    refTurnoListTable,

    statusFilter,
    typeFilter,

    resolveInvoiceStatusVariantAndIcon,
    resolveClientAvatarVariant,
    resolvePaperStatusVariant,
    refetchData,
    refreshStatus
  }
}
