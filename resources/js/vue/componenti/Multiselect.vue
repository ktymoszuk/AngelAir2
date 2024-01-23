<template>
    <div class="position-relative w-75" style="z-index: 9999;">
        <div class="input-group cursor-pointer" @click="mostraElementi = !mostraElementi">
            <span v-if="nameSelect" class="btn border border-end-0 bg-light" style="cursor: auto">{{ nameSelect }}</span>
            <div class="form-control d-flex justify-content-between align-items-center pe-2">
                <span>
                    Seleziona
                </span>
                <span v-if="mostraElementi" class="material-icons icon-centered fs-4">
                    expand_less
                </span>
                <span v-else class="material-icons icon-centered fs-4">
                    expand_more
                </span>
            </div>
        </div>
        <div v-if="mostraElementi" class="border rounded-2 container-fluid position-absolute multiple-checkbox" style="top:38px;left:0;">
            <div v-if="options" class="row">
                <div v-for="(elemento, t) in options" class="col-12 d-flex multiple-checkbox-option">
                    <div class="checkbox-wrapper ms-1 me-2">
                        <input hidden class="ms-3 me-4" type="checkbox" :checked="giaSelezionati.includes(t)" :id="'elemento-' + t" name="tratta">
                        <label :for="'elemento-' + t" @click="toggleElemento(t)">
                            <div class="tick_mark"></div>
                        </label>
                    </div>
                    <label :for="'elemento-' + t" class="w-100" @click="toggleElemento(t)">{{ elemento[optionLabel] }}</label>
                </div>
            </div>
        </div>
        <div v-if="elementiSelezionati">
            <span v-for="(elemento, t2) in options" class="badge bg-primary me-1 fw-normal" :class="{ 'd-none': !elementiSelezionati.includes(t2) }">
                {{ elemento[optionLabel] }}
            </span>
        </div>
    </div>
</template>

<script>

// PROPS NECESSARI
// 'dbid': l'id dell'elemento che andrà nel value nel db
// 'options': dati per le options
// 'optionLabel': variabile che prelevo dalle options per creare il label del checkbox
// 'nameSelect': nome da inserire del campo select
// 'nameCheckbox': il 'name' che mi serve per il db
// 'giaSelezionati': index degli elementi già selezionati

// NEL COMPONENTE PADRE NEI DATA AGGIUNGERE:
// elementiSelezionatiMultiselect: [],

// SE E' UN'AGGIUNTA, NEI DATA AGGIUNGERE
// elementiGiaSelezionati: [],
// ALTRIMENTIFACCIO UNA FUNZIONE CHE MI QUESTO ARRAY
// CON GLI INDEX DEGLI ELEMENTI CHE DEVONO ESSERE CHECKED

// NEL COMPONENTE PADRE NEI METHODS AGGIUNGERE:
// updateElementiMultiselect(selectedItems) {
//     this.elementiSelezionatiMultiselect = selectedItems;
// },

// NEL FORM DEL COMPONENTE PADRE AGGIUNGERE UN INPUT:HIDDEN
// <input type="hidden" name="Tratte" :value="elementiSelezionatiMultiselect">

export default {
    data() {
        return {
            mostraElementi: false,
            elementiSelezionati: [],
        }
    },
    props: {
        'dbid': {
            required: false,
        },
        'options': {
            required: false,
        },
        'optionLabel': {
            required: false,
        },
        'nameSelect': {
            required: false,
        },
        'giaSelezionati': {
            required: false,
        },
        'nameCheckbox': {
            required: false,
        },
    },
    watch: {
        giaSelezionati() {
            this.elementiSelezionati = this.giaSelezionati;
            this.$emit('update:selected', this.giaSelezionati);
        }
    },
    methods: {
        toggleElemento(index) {
            const elementiSelezionati = this.giaSelezionati;
            let selezionato = false;
            if (this.elementiSelezionati.includes(index)) {
                selezionato = true;
            }
            if (selezionato === true) {
                const i = this.elementiSelezionati.indexOf(index);
                this.elementiSelezionati.splice(i, 1);
                this.elementiSelezionati.push(index);
            }

            this.options.forEach((option, i) => {
                if (this.elementiSelezionati.includes(i)) {
                    elementiSelezionati.push(option[this.dbid]);
                }
            });
            this.$emit('update:selected', elementiSelezionati);
        },
    }
}
</script>

<style scoped>
    .multiple-checkbox {
        max-height: 200px;
        overflow: scroll;
        background-color: #FFFFFF;
    }
    .multiple-checkbox-option:hover {
        background-color: #ebebeb;

    }

    .checkbox-wrapper * {
  -webkit-tap-highlight-color: transparent;
  outline: none;
}

.checkbox-wrapper input[type="checkbox"] {
  display: none;
}

.checkbox-wrapper label {
  --size: 20px;
  --shadow: calc(var(--size) * .07) calc(var(--size) * .1);
  position: relative;
  display: block;
  width: var(--size);
  height: var(--size);
  margin: 0 auto;
  background-color: #4158D0;
  background-image: linear-gradient(43deg, #4158D0 0%, #C850C0 46%, #FFCC70 100%);
  border-radius: 50%;
  box-shadow: 0 var(--shadow) #ffbeb8;
  cursor: pointer;
  transition: 0.2s ease transform, 0.2s ease background-color,
      0.2s ease box-shadow;
  overflow: hidden;
  z-index: 1;
}

.checkbox-wrapper label:before {
  content: "";
  position: absolute;
  top: 50%;
  right: 0;
  left: 0;
  width: calc(var(--size) * .7);
  height: calc(var(--size) * .7);
  margin: 0 auto;
  background-color: #fff;
  transform: translateY(-50%);
  border-radius: 50%;
  box-shadow: inset 0 var(--shadow) #ffbeb8;
  transition: 0.2s ease width, 0.2s ease height;
}

.checkbox-wrapper label:hover:before {
  width: calc(var(--size) * .55);
  height: calc(var(--size) * .55);
  box-shadow: inset 0 var(--shadow) #ff9d96;
}

.checkbox-wrapper label:active {
  transform: scale(0.9);
}

.checkbox-wrapper .tick_mark {
  position: absolute;
  top: -1px;
  right: 0;
  left: calc(var(--size) * -.05);
  width: calc(var(--size) * .6);
  height: calc(var(--size) * .6);
  margin: 0 auto;
  margin-left: calc(var(--size) * .14);
  transform: rotateZ(-40deg);
}

.checkbox-wrapper .tick_mark:before,
  .checkbox-wrapper .tick_mark:after {
  content: "";
  position: absolute;
  background-color: #fff;
  border-radius: 2px;
  opacity: 0;
  transition: 0.2s ease transform, 0.2s ease opacity;
}

.checkbox-wrapper .tick_mark:before {
  left: 0;
  bottom: 0;
  width: calc(var(--size) * .1);
  height: calc(var(--size) * .3);
  box-shadow: -2px 0 5px rgba(0, 0, 0, 0.23);
  transform: translateY(calc(var(--size) * -.68));
}

.checkbox-wrapper .tick_mark:after {
  left: 0;
  bottom: 0;
  width: 100%;
  height: calc(var(--size) * .1);
  box-shadow: 0 3px 5px rgba(0, 0, 0, 0.23);
  transform: translateX(calc(var(--size) * .78));
}

.checkbox-wrapper input[type="checkbox"]:checked + label {
  background-color: #4158D0;
  background-image: linear-gradient(43deg, #4158D0 0%, #C850C0 46%, #FFCC70 100%);
  box-shadow: rgba(0, 0, 0, 0.3) 0px 19px 38px, rgba(0, 0, 0, 0.22) 0px 15px 12px;
}

.checkbox-wrapper input[type="checkbox"]:checked + label:before {
  width: 0;
  height: 0;
}

.checkbox-wrapper input[type="checkbox"]:checked + label .tick_mark:before,
  .checkbox-wrapper input[type="checkbox"]:checked + label .tick_mark:after {
  transform: translate(0);
  opacity: 1;
}

</style>