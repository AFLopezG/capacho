<template>
  <q-page padding>
    <q-table
      title="LISTADO DE SERVICIOS Y PRECIO"
      :rows="servicios"
      :columns="columns"
      row-key="name"
      :filter="filter">
      <template v-slot:top-right>
        <q-btn dense color="green" icon="add_circle" @click="servicio={monto:0}; dialogreg=true" />
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
      
      <template v-slot:body-cell-imagen="props">
          <q-td key="imagen" :props="props">
            <img :src="$url+'../imagen/'+props.row.imagen" :alt="props.row.nombre" srcset="" height="50px" width="50px">
          </q-td>
      </template>
          <template v-slot:body-cell-op="props">

          <q-td key="opcion" :props="props">
            <q-btn dense round flat color="info" @click="cargarImg(props.row)" icon="image" />
            <q-btn dense round flat color="yellow" @click="editRow(props.row)" icon="edit" />
          </q-td>

      </template>
    </q-table>
    <q-dialog v-model="dialogimg" persistent>
        <q-card>
            <q-card-section class="row items-center">
                <q-avatar icon="image" color="primary" text-color="white" />
                <span class="q-ml-sm">CARGAR IMAGEN </span>
            </q-card-section>
            <q-card-section>
                <label for="">Imagen / FOTO</label><br>
              <q-uploader
                accept=".jpg, .png, .jpeg"
                @added="getImg"
                auto-upload
                max-files="1"
                label="Subir foto"
                flat
                max-file-size="5000000"
                @rejected="onRejected"
                bordered
              />
            </q-card-section>
            <q-card-actions align="right">
                <q-btn flat label="Cancel" color="primary" v-close-popup />
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-dialog v-model="dialogreg" persistent>
      <q-card>
        <q-card-section class="row items-center" >
          <q-avatar size="lg" icon="design_services" color="primary" text-color="white" />
          <span class="q-ml-sm" v-if="servicio.id==undefined">REGISTRAR SERVICIO</span>
          <span class="q-ml-sm" v-else>MODIFICAR SERVICIO</span>
        </q-card-section>
        <q-form
          @submit="registro"
          class="q-gutter-md"
        >
        <q-card-section>
          <div class="row">
            <div class="col-12"><q-input v-model="servicio.nombre" type="text" label="Nombre" /></div>
            <div class="col-12"><q-input v-model="servicio.monto" type="number" step="0.01" label="Monto" /></div>
            <div class="col-12"><q-select v-model="unit" :options="unidades" option-label="nombre" label="Unidad" /></div>
          </div>
        </q-card-section>
          
        <q-card-actions align="right">
          <q-btn flat label="Cancel" color="primary" v-close-popup />
          <q-btn flat label="REGISTRAR" color="green" type="submit" v-if="servicio.id==undefined"/>
          <q-btn flat label="MODIFICAR" color="yellow" type="submit" v-else/>
        </q-card-actions>
          </q-form>
        </q-card>
    </q-dialog>
  </q-page>
</template>
<script>
export default {
  name:'servicioPage',
  data() {
    return {
      filter:'',
      dialogimg:false,
      dialogreg:false,
      servicios:[],
      servicio:{},
      unidades:[],
      unit:{},
      columns:[
        {name:'op',field:'op',label:'OP'},
        {name:'nombre',field:'nombre',label:'NOMBRE'},
        {name:'estado',field:'estado',label:'ESTADO'},
        {name:'monto',field:'monto',label:'MONTO'},
        {name:'unit',field:row=>row.unit.nombre,label:'UNIDAD'},
        {name:'imagen',field:'imagen',label:'IMAGEN'},
      ]

      
    }
  },
  mounted(){
    this.getServicios()
    this.getUnit()
  },
  methods:{
    registro(){
      console.log(this.servicio)
    },
    getUnit(){
      this.$api.get('unit').then((res) => {
        this.unidades=res.data
        this.unit=this.unidades[0]
      })
    },
    editRow(serv){
      this.servicio=serv
      this.unit=serv.unit
      this.dialogreg=true
    },
    onRejected (rejectedEntries) {
            console.log(rejectedEntries)
          this.$q.notify({
            type: 'negative',
            message: `${rejectedEntries.length} el archivo paso las restricciones de validación`
          })
        },
        cargarImg(b){
            this.servicio=b;
            this.dialogimg=true;
        },
        getImg(event){
            let dialog = this.$q.dialog({
        message: 'Subiendo... 0%',
      })
      let percentage = 0
      const fd = new FormData()
      fd.append('file', event[0])
      fd.append('id', this.servicio.id)
        this.$api.post('uploadImg', fd,
          {
            headers: { 'Content-Type': 'multipart/form-data' },
            onUploadProgress: (progressEvent) => {
              percentage = Math.round((progressEvent.loaded / progressEvent.total) * 100)
              dialog.update({
                message: `Subiendo... ${percentage}%`
              })
              if (percentage>=100){
                dialog.hide()
              }
            }
          })
          .then(res => {
            console.log(res.data)
            this.dialogimg=false
            this.getServicios()
            this.loading=false
          })
          .catch(err => reject(err))
        },
    getServicios(){
      this.$api.get('servicio').then(res => {
        this.servicios=res.data
      })

    }
  }
}
</script>