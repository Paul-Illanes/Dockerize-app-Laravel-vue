import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import axios from '@axios'
import { paginateArray, sortCompare } from '@/@fake-db/utils'
// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useBajaPersonalList() {
  // Use toast
  const toast = useToast()

  const refBajaPersonalListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'index', label: '#', sortable: true },
    { key: 'cod_planilla', sortable: true },
    { key: 'c_l', label:'Regimen', sortable: true },
    { key: 'dni', sortable: true },
    { key: 'nombres', sortable: true },
    { key: 'fecha_ultimo_dia', sortable: true },
    { key: 'fecha_cese', sortable: true },
    { key: 'status_baja', sortable: true },
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

  const dataMeta = computed(() => {
    const localItemsCount = refBajaPersonalListTable.value ? refBajaPersonalListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalInvoices.value,
    }
  })

  const refetchData = () => {
    refBajaPersonalListTable.value.refresh()
    
  }

  watch([currentPage, perPage, searchQuery, statusFilter, typeFilter], () => {
    refetchData()
  })

  const fetchBajas = (ctx, callback) => {
    store
      .dispatch('app-baja/fetchBajas', {
        q: searchQuery.value,
        perPage: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        sortDesc: isSortDirDesc.value,
        status_baja: statusFilter.value,
        type: typeFilter.value,
      })
      .then(response => {
        const { bajas, total } = response[0]

        callback(bajas)
        totalInvoices.value = total
      })
      .catch((error) => {
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

  const resolvePaperStatusVariant = status_baja => {
    if (status_baja === 0) return 'warning'
    if (status_baja === 1) return 'success'
    if (status_baja === 2) return 'danger'
    if (status_baja === 3) return 'secondary'
    return 'danger'
  }

  const resolveInvoiceStatusVariantAndIcon = status_baja => {
    if (status_baja === 0) return { variant: 'warning', label: 'observado' }
    if (status_baja === 1) return { variant: 'success', label: 'anulado' }
    if (status_baja === 2) return { variant: 'info', label: 'aprobado' }
    return { variant: 'secondary', icon: 'XIcon', label: 'fer' }
  }

  const resolveClientAvatarVariant = status_baja => {
    if (status_baja === 'Partial Payment') return 'primary'
    if (status_baja === 'Paid') return 'danger'
    if (status_baja === 'Downloaded') return 'secondary'
    if (status_baja === 'Draft') return 'warning'
    if (status_baja === 'Sent') return 'info'
    if (status_baja === 'Past Due') return 'success'
    return 'primary'
  }

  return {
    fetchBajas,
    tableColumns,
    perPage,
    currentPage,
    totalInvoices,
    dataMeta,
    perPageOptions,
    searchQuery,
    sortBy,
    isSortDirDesc,
    refBajaPersonalListTable,

    statusFilter,
    typeFilter,

    resolveInvoiceStatusVariantAndIcon,
    resolveClientAvatarVariant,
    resolvePaperStatusVariant,
    refetchData,
  }
}
