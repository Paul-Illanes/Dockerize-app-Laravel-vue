import mock from './mock'

/* eslint-disable import/extensions */

// JWT
import './jwt'

// Apps
import './data/apps/invoice'

/* eslint-enable import/extensions */

mock.onAny().passThrough() // forwards the matched request over network
