import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router'
import 'vant/lib/index.css'
import { Button, List, Cell, CellGroup, Image as VanImage, Grid, GridItem, Loading, Toast, Dialog, Icon, PullRefresh } from 'vant'

const app = createApp(App)

app.use(router)
app.use(Button)
app.use(List)
app.use(Cell)
app.use(CellGroup)
app.use(VanImage)
app.use(Grid)
app.use(GridItem)
app.use(Loading)
app.use(Toast)
app.use(Dialog)
app.use(Icon)
app.use(PullRefresh)

app.mount('#app')
