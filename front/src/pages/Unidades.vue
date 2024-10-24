<template>
  <q-page>
    <q-table
      title="Listado de Unidades"
      :rows="unidades"
      :columns="columns"
      row-key="name"
      :filter="filter"
    >
    <template v-slot:top-right>
        <q-btn color="primary" icon="add_circle_outline"  @click="unidad={};dialogReg=true" dense />
        <q-input borderless dense debounce="300" v-model="filter" placeholder="Search">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
      <template v-slot:body-cell-op="props">
                <q-td key="op" :props="props">
                    <q-btn flat size="sm" color="yellow" icon="edit"  @click="unidad=props.row; dialogReg=true" />

                </q-td>

            </template>
    </q-table>
    <q-dialog v-model="dialogReg" persistent>
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar size="lg" icon="build" color="primary" text-color="white" />
          <span class="q-ml-sm" v-if="unidad.id==undefined">REGISTRAR UNIDAD</span>
          <span class="q-ml-sm" v-else>MODIFICAR UNIDAD</span>
        </q-card-section>
        <q-form
          @submit="registrar"
          @reset="onReset"
          class="q-gutter-md"
        >
       <q-card-section>
          <div class="row">
            <div class="col-12"><q-input v-model="unidad.nombre" type="text" label="Nombre *" /></div>
            <div class="col-12"><q-input v-model="unidad.direccion" type="text" label="Direccion" /></div>
            <div class="col-12"><q-input v-model="unidad.telefono" type="text" label="Telefono" /></div>
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Cancelar" color="red" v-close-popup />
          <q-btn flat label="Registrar" color="green" type="submit" v-if="unidad.id==undefined"/>
          <q-btn flat label="Modificar" color="yellow" type="submit" v-else/>
        </q-card-actions>
        </q-form>
      </q-card>
    </q-dialog>
  </q-page>
</template>
<script>
export default {
  name:'unidadPage',
  data() {
    return {
      filter:'',
      unidades:[],
      unidad:{},
      dialogReg:false,
      columns:[
        {label:'OP',name:'op',field:'op'},
        {label:'NOMBRE',name:'nombre',field:'nombre'},
        {label:'DIRECCION',name:'direccion',field:'direccion'},
        {label:'TELFONO',name:'telefono',field:'telefono'},
      ]
    }
  },
  created(){
    this.getUnit()
  },
  methods:{
    getUnit(){
      this.$api.get('unit').then(res => {
        this.unidades=res.data
      })
    },

    registrar(){
      if(this.unidad.nombre==undefined || this.unidad.nombre=='')
        return false
      if(this.unidad.id==undefined){
        this.$api.post('unit',this.unidad).then(() => {
          this.getUnit()

        })
      }
      else{
        this.$api.put('unit/'+this.unidad.id,this.unidad).then(() => {
          this.getUnit()
        })
      }
      this.dialogReg=false
    }
  }
}
</script>
