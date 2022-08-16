import { ref, watch, computed } from '@vue/composition-api'
import store from '@/store'
import axios from '@axios'
import { paginateArray, sortCompare } from '@/@fake-db/utils'
// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default function useInformeCeseList() {
  // Use toast
  const toast = useToast()

  const refInformeCeseListTable = ref(null)

  // Table Handlers
  const tableColumns = [
    { key: 'index', label: '#', sortable: true },
    { key: 'nombre', sortable: true },
    { key: 'dni', sortable: true },
    { key: 'fecha_ingreso', sortable: true },
    { key: 'fecha_cese', sortable: true },
    { key: 'PDF', sortable: false },
    { key: 'actions' },
  ]
  const perPage = ref(10)
  const totalInvoices = ref(0)
  const currentPage = ref(1)
  const perPageOptions = [10, 25, 50, 100]
  const searchQuery = ref('')
  const sortBy = ref('id')
  const isSortDirDesc = ref(true)
  const typeFilter = ref(null)
  const refreshStatus = ref(0)

  const dataMeta = computed(() => {
    const localItemsCount = refInformeCeseListTable.value ? refInformeCeseListTable.value.localItems.length : 0
    return {
      from: perPage.value * (currentPage.value - 1) + (localItemsCount ? 1 : 0),
      to: perPage.value * (currentPage.value - 1) + localItemsCount,
      of: totalInvoices.value,
    }
  })

  const refetchData = () => {
    refInformeCeseListTable.value.refresh()
    
  }

  watch([currentPage, perPage, searchQuery, refreshStatus], () => {
    refetchData()
  })

  const fetchCeses = (ctx, callback) => {
    store
      .dispatch('app-cese/fetchInformeCese', {
        q: searchQuery.value,
        perPage: perPage.value,
        page: currentPage.value,
        sortBy: sortBy.value,
        sortDesc: isSortDirDesc.value,
        refreshStatus: refreshStatus.value,
      })
      .then(response => {
        console.log(response[0])
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
    fetchCeses,
    tableColumns,
    perPage,
    currentPage,
    totalInvoices,
    dataMeta,
    perPageOptions,
    searchQuery,
    sortBy,
    isSortDirDesc,
    refInformeCeseListTable,

    resolveInvoiceStatusVariantAndIcon,
    resolveClientAvatarVariant,
    resolvePaperStatusVariant,
    refetchData,
    refreshStatus
  }
}
