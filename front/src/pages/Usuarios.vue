<template>
  <div class="q-pa-md">
    <q-btn
      label="Nuevo usuario"
      color="positive"
      @click="regDialog"
      icon="add_circle"
      class="q-mb-xs"
    />

    <q-dialog v-model="alert" full-width>
      <q-card >
        <q-card-section class="bg-green-14 text-white" v-if="dato.id==undefined">
          <div class="text-h7"><q-icon name="add_circle" /> REGISTRO DE NUEVO USUARIO</div>
        </q-card-section>
        <q-card-section class="bg-yellow-14 text-white" v-else>
          <div class="text-h7"><q-icon name="add_circle" /> MODIFICAR USUARIO</div>
        </q-card-section>
        <q-card-section class="q-pt-xs">
          <q-form @submit="onSubmit" @reset="onReset" class="q-gutter-md">
            <div class="row">
              <div class="col-12"><q-input outlined v-model="dato.nombre"   type="text" label="Nombre " hint="Ingresar Nombre" dense lazy-rules :rules="[(val) => val.length > 0 || 'Por favor ingresa datos']"/></div>
              <div class="col-12"><q-input outlined dense v-model="dato.email" type="email" label="Email" hint="Correo electronico" lazy-rules :rules="[(val) => val.length > 0 || 'Por favor ingresa datos']" /></div>
              <div class="col-12"><q-input outlined dense v-model="dato.name" label="Cuenta" hint="Cuenta" lazy-rules :rules="[(val) => val.length > 0 || 'Por favor ingresa datos']" /></div>
              <div class="col-12" v-if="dato.id==undefined"><q-input outlined dense v-model="dato.password" label="Contraseña" hint="Contraseña" lazy-rules :rules="[(val) => val.length > 0 || 'Por favor ingresa datos']" :type="typePassword?'password':'text'">
                <template v-slot:append>
                          <q-icon @click="typePassword=!typePassword" :name="typePassword?'visibility':'visibility_off'" />
                </template>
              </q-input>
            </div>
              <div class="col-12"><q-input outlined dense v-model="dato.fechalimite" type="date" label="Fecha Limite" lazy-rules :rules="[(val) => val.length > 0 || 'Por favor ingresa datos']" /></div>
              <div class="col-12"><q-select outlined dense v-model="dato.rol" :options="roles" label="Roles" lazy-rules :rules="[(val) => val.length > 0 || 'Por favor ingresa datos']" /></div>
              <div class="col-12"><q-select outlined dense v-model="unidad" :options="unidades" label="Unidad"  option-label="nombre"/></div>

              </div>

            <div>
              <q-btn label="REGISTRAR" type="submit" color="green" icon="add_circle" v-if="dato.id==undefined"/>
              <q-btn label="MODIFICAR" type="submit" color="yellow" icon="edit"  v-else/>
              <q-btn label="Cancelar" icon="delete" color="negative" v-close-popup />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

    <q-table dense :filter="filter" title="REGISTRO DE USUARIOS" :rows="data" :columns="columns" row-key="name" :rows-per-page-options="[50,100]">
      <template v-slot:top-right>
        <q-input outlined dense debounce="300" v-model="filter" placeholder="Search">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>

          <template v-slot:body-cell-estado="props">
          <q-td key="estado" :props="props">
            <q-badge :color="props.row.estado=='ACTIVO'?'green':'red'"  :label="props.row.estado" @click="cambioEstado(props.row)" />
          </q-td>
      </template>
          <template v-slot:body-cell-opcion="props">

          <q-td key="opcion" :props="props">
            <q-btn dense round flat color="yellow" @click="editRow(props)" icon="edit" />
            <q-btn dense round flat color="positive" @click="cambiopass(props)" icon="vpn_key" />
            <q-btn dense round flat color="red" @click="deleteRow(props)" icon="delete" ></q-btn>
          </q-td>

      </template>
    </q-table>


    <q-dialog v-model="dialog_del">
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar icon="clear" color="red" text-color="white" />
          <span class="q-ml-sm">Seguro de eliminar Registro.</span>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Eliminar" color="deep-orange" @click="onDel" />
          <q-btn flat label="Cancelar" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>

  </div>
</template>

<script>
import { date } from 'quasar'
import moment from 'moment'
import {globalStore} from   '../stores/globalStore'

export default {
  name: 'UserPage',
  data () {
    return {
      store:globalStore(),
      roles:['ADMIN','CAJERO'],
      alert: false,
      dialog_mod: false,
      dialog_del: false,
      typePassword: true,
      unidad:{},
      fecha: date.formatDate(new Date(), 'YYYY-MM-DD'),
      filter: '',
      dato: { rol:'CAJERO',fechalimite: (moment(this.fecha).add(36, 'months').format('YYYY-MM-DD')) },
      model: '',
      options: [],
      props: [],
      unidades: [],
      filterU:[],
      uni: {},
      columns: [
        { name: 'opcion', label: 'OPCIÓN', field: 'action', sortable: false },
        { name: 'name', align: 'left', label: 'NOMBRE ', field: 'name', sortable: true },
        { name: 'email', align: 'left', label: 'E-MAIL', field: 'email', sortable: true },
        { name: 'fechalimite', align: 'left', label: 'FECHA LIM', field: 'fechalimite', sortable: true },
        { name: 'estado', align: 'left', label: 'ESTADO', field: 'estado', sortable: true },
        { name: 'unidad', align: 'left', label: 'UNIDAD', field: row=>row.unit.nombre, sortable: true },
      ],
      data: []
    }
  },

  mounted () {
    this.misdatos()
    this.getUnit()
  },
  methods: {
    getUnit(){
      this.$api.get('unit').then((res) => {
        this.unidades=res.data
      })
    },

    cambioEstado(user1){
      this.$api.post('cambioEstado/' + user1.id).then(() => {
        this.misdatos()
      })

    },

    regDialog () {
      this.dato = { rol:'CAJERO',fechalimite: (moment(this.fecha).add(12, 'months').format('YYYY-MM-DD')) }
      this.alert = true
    },
    misdatos () {
      this.$q.loading.show()
      this.$api.get('user').then((res) => {
        console.log(res.data)
        this.data = res.data
        this.$q.loading.hide()
      })
    },
    editRow (item) {
      this.dato = item.row
      this.unidad=this.dato.unit
      this.alert = true
    },
    deleteRow (item) {
      this.dato = item.row
      this.dialog_del = true
    },
    onSubmit () {
      if(this.unidad.id==undefined)
        return false
      this.dato.unit_id=this.unidad.id
      this.$q.loading.show()
      if(this.dato.id==undefined){
      this.$api.post('user', this.dato).then(() => {
        // console.log(res.data)
        this.$q.notify({
          color: 'green-4',
          textColor: 'white',
          icon: 'cloud_done',
          message: 'Creado correctamente'
        })
        this.dato = {rol:'CAJERO', fechalimite: (moment(this.fecha).add(12, 'months').format('YYYY-MM-DD')) }
        this.alert = false
        this.misdatos()
      }).catch(err => {
        console.log(err.response.data)
        this.$q.notify({
          message: err.response.data.message,
          icon: 'close',
          color: 'red'
        })})
        this.$q.loading.hide()      
      }
        else{
          this.$api.put('user/'+this.dato.id, this.dato).then(() => {
          this.dato = {rol:'CAJERO', fechalimite: (moment(this.fecha).add(12, 'months').format('YYYY-MM-DD')) }
        this.alert = false
        this.misdatos()
        })
        this.$q.loading.hide()

        }
      
    },

    onDel () {
      this.$q.loading.show()
      this.$api.delete('user/' + this.dato.id)
        .then(() => {
          this.$q.notify({
            color: 'green-4',
            textColor: 'white',
            icon: 'cloud_done',
            message: 'Eliminado correctamente'
          })
          this.dialog_del = false
          this.misdatos()
        }).catch(err => {
          this.$q.loading.hide()
          this.$q.notify({
            message: err.response.data.message,
            icon: 'error',
            color: 'red'
          })
        })
    },
    onReset () {
      this.dato.nombre = null
      this.dato.inicio = 0
      this.dato.fin = 0
    },
    cambiopass (i) {
      // console.log(i.row);
      this.$q.dialog({
        title: 'CAMBIAR PASSWORD',
        message: 'Ingresar nueva contraseña',
        prompt: {
          model: '',
          type: 'text' // optional
        },
        cancel: true,
        persistent: true
      }).onOk(data => {
        this.$q.loading.show()
        this.$api.put('updatePassword/' + i.row.id, { password: data }).then(() => {
          this.$q.loading.hide()
        })
      }).onCancel().onDismiss()
    }
  }
}
</script>
