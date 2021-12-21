<template>
  <div class="card px-5 pt-5" v-if="onClickEdit == 'main'">
    <div class="col-xl-12 mx-auto">
      <h3 class="text-center mb-10">Form element</h3>
    </div>
    <div class="form-group d-flex flex-column">
      <div class="d-flex flex-column">
        <draggable
          class="page-container"
          v-model="baseObjToGen.schema.fields"
          v-bind="dragOptions"
          @start="isDragging = true"
          @end="isDragging = false"
        >
          <div
            class="page-element"
            v-for="(item, i) in baseObjToGen.schema.fields"
            :key="item.model"
            :i="i"
            @click.self="onEditElement(item, i)"
          >
            <div title="Show in form" class="checkbox-inline">
              <label
                class="
                  checkbox checkbox-outline checkbox-outline-2x checkbox-primary
                "
                :class="{ 'checkbox-disabled': item.disableEdit == true }"
              >
                <input
                  type="checkbox"
                  name="Checkboxes15"
                  v-model="item.visible"
                  :disabled="item.disableEdit == true"
                />
                <span></span>
              </label>
            </div>
            <h6 @click="onEditElement(item, i)" class="mb-0 ml-3">
              {{ item.label }}
            </h6>
            <div class="flex-grow-1"></div>
            <i
              title="Delete"
              v-if="!!item.recentlyAdded"
              class="fas fa-trash-alt mr-2 text-danger"
              @click="onDeleteElement(baseObjToGen.schema.fields, i)"
            ></i>
            <i title="Drag to change position" class="fas fa-grip-vertical mr-2" style="cursor: move;"></i>
            <i
              title="Edit"
              @click="onEditElement(item, i)"
              class="fas fa-angle-right"
            ></i>
          </div>

        </draggable>
        <!-- <Tips class="mt-5 mb-0 tips-custom">
            Press and hold the element then drag up/down to change the position of it
        </Tips> -->
        <button
            v-b-modal.elementType
            class="btn btn-primary px-10 mb-5"
        >
            <span class="svg-icon svg-icon-lg svg-icon-white">
              <inline-svg src="media/svg/icons/Code/Plus.svg" />
            </span>
            Add element
        </button>

        <!-- modal chọn loại element -->
        <b-modal
        id="elementType"
        ref="elementType"
        title="Choose type of element"
        >
        <b-form-select
            v-model="typeElementSelected"
            :options="typeElementOptions"
            size="sm"
            class="mt-3"
        ></b-form-select>
        <template #modal-footer>
            <button
            class="btn btn-primary btn-md"
            @click="onSubmitElement(typeElementSelected)"
            >
            OK
            </button>
        </template>
        </b-modal>
      </div>
    </div>
  </div>

  <div
    v-else-if="onClickEdit == 'EditElement'"
    class="card p-5 pb-0"
  >
    <EditElement
      @closeOnSelect="closeOnSelect"
      :objToEdit="objToEdit"
      @onApplyChange="onApplyChangeElement"
    />
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import {
  BFormSelect,
  BFormInput,
  BSpinner,
  BButton,
  BFormCheckbox,
  BFormTextarea,
  BOverlay,
  BFormGroup,
} from "bootstrap-vue";
import draggable from "vuedraggable";
import EditElement from "@/view/pages/registration/partials/Form/EditElement.vue";
export default {
  components: {
    BFormSelect,
    BFormInput,
    BSpinner,
    BButton,
    BFormCheckbox,
    BFormTextarea,
    BOverlay,
    BFormGroup,
    EditElement,
    draggable,
  },
  data() {
    return {
      onClickEdit: "main",
      objToEdit: {},
      indexOfElement: 0,
      typeElementSelected: "input",
      typeElementOptions: [
        { value: "input", text: "Input" },
        { value: "radios", text: "Radios" },
        { value: "select", text: "Select" },
        { value: "textArea", text: "Textarea" },
        { value: "checkbox", text: "Check box" },
      ],
      editable: true,
      isDragging: false,
      delayedDragging: false,
    };
  },
  methods: {
    ...mapActions(["changeBaseObj"]),
    /**Về màn hình formtab */
    closeOnSelect() {
      this.onClickEdit = "main";
    },
    /**Mở form sửa element */
    onEditElement(item, i) {
      this.objToEdit = item;
      this.onClickEdit = "EditElement";
      this.indexOfElement = i;
    },
    onEdit(page) {
      this.onClickEdit = page;
    },
    /**
     * Bắt sự kiện thay đổi object từ component EditElement
     * @param objEdited: Obj đã chỉnh sửa
     */
    onApplyChangeElement(objEdited) {
      this.baseObjToGen.schema.fields[this.indexOfElement] = objEdited;
      var payload = this.baseObjToGen;
      this.changeBaseObj(payload);
    },
    /**Xóa element
     * @param arr: mảng schema.fields
     * @param i: Vị trí của phần tử cần xóa
     */
    onDeleteElement(arr, i) {
      if (!!arr[i]) {
        // lấy ra tên model cần xóa trong schema.fields[i]
        let t = arr[i].model;
        // xóa element này trong mảng schema.fields
        arr.splice(i, 1);
        // Xóa property đó trong object model
        delete this.baseObjToGen.model[t];
        //gán field bằng mảng vừa cắt phần tử
        this.baseObjToGen.schema.fields = arr;
        this.changeBaseObj(this.baseObjToGen);
      }
    },
    /**
     * Chọn loại element
     */
    onSubmitElement(value) {
        if(!!value){
            let modelId = "ext_" + this.makeid(8);
            switch (value) {
              case "input":
                var newElement = {
                  label: "New element",
                  model: modelId,
                  placeholder: "",
                  featured: false,
                  required: false,
                  type: "input",
                  inputType: "text",
                  visible: false,
                  disableEdit: false,
                  recentlyAdded: true,
                  styleClasses: ["apply-font-labelInput"],
                  validator: "required",
                };
                break;
              case "radios":
                var newElement = {
                  type: "radios",
                  label: "New element",
                  model: modelId,
                  featured: false,
                  required: false,
                  visible: false,
                  disabled: false,
                  recentlyAdded: true,
                  styleClasses: ["apply-font-labelInput"],
                  values: [{ id: "0", name: "Default choice" }],
                  validator: "required",
                  radiosOptions: {
                    value: "id",
                    name: "name",
                  },
                };
                break;
              case "select":
                var newElement = {
                  type: "select",
                  label: "New element",
                  model: modelId,
                  featured: false,
                  required: false,
                  visible: false,
                  disabled: false,
                  recentlyAdded: true,
                  styleClasses: ["apply-font-labelInput"],
                  values: [{ id: "0", name: "Default choice" }],
                  validator: "required",
                  default: "0",
                };
                break;
              case "textArea":
                var newElement = {
                  label: "New element",
                  model: modelId,
                  featured: false,
                  required: false,
                  type: "textArea",
                  hint: "",
                  max: 500,
                  placeholder: "",
                  rows: 4,
                  visible: false,
                  disabled: false,
                  recentlyAdded: true,
                  styleClasses: ["apply-font-labelInput"],
                  validator: "required",
                };
                break;
              case "checkbox":
                var newElement = {
                  label: "New element",
                  model: modelId,
                  featured: false,
                  required: false,
                  type: "checkbox",
                  default: false,
                  visible: false,
                  disabled: false,
                  recentlyAdded: true,
                  styleClasses: ["apply-font-labelInput"],
                  validator: "required",
                };
                break;
              default:
                  value = "input";
                  break;
            }
            this.baseObjToGen.model[modelId] = null;
            this.baseObjToGen.schema.fields.push(newElement);
            // Khi thêm được thì mở form edit
            var index = this.baseObjToGen.schema.fields.length - 1;
            var item = this.baseObjToGen.schema.fields[index];
            this.onEditElement(item, index);
            this.changeBaseObj(this.baseObjToGen);
            this.$refs["elementType"].hide();
        }else{
            this.$refs["elementType"].hide();
        }
    },
    orderList() {
      this.list = this.list.sort((one, two) => {
        return one.order - two.order;
      });
    },
    onMove({ relatedContext, draggedContext }) {
      const relatedElement = relatedContext.element;
      const draggedElement = draggedContext.element;
      return (
        (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed
      );
    },
    backToTemplate(){
        this.$emit("backToTemplate")
    }
  },
  computed: {
    ...mapGetters(["baseObjToGen"]),
    id() {
      return this.$route.params.id;
    },
    dragOptions() {
      return {
        animation: 0,
        group: "description",
        disabled: !this.editable,
        ghostClass: "ghost",
      };
    },
    listString() {
      return JSON.stringify(this.list, null, 2);
    },
  },
};
</script>

<style lang="scss" scoped>
.page-container {
  width: 100%;
}
.page-element {
  color: #7E8299;
  border: 1px solid #7E8299;
  padding: 10px;
  border-radius: 5px;
  width: 100%;
  display: flex;
  align-items: center;
  margin-bottom: 5px;
  cursor: pointer;
}
.card{
    box-shadow: none !important;
    border-radius: unset !important;
}

</style>
<style>
.flip-list-move {
  transition: transform 0.5s;
}
.tips-custom .alert-custom{
    padding: 0.5rem 1rem !important;
}
.no-move {
  transition: transform 0s;
}

.ghost {
  opacity: 1;
  background: #78d2fc;
}
.skip {
  position: absolute;
  top: 105px;
  right: 50px;
  z-index: 5;
  cursor: pointer;
}
.group-nav > .nav-item > .nav-link.active, .nav-link:hover{
    color: #7E8299;
}
.group-nav .nav-link:hover:not(.disabled), .nav.nav-tabs.nav-tabs-line .nav-link.active, .nav.nav-tabs.nav-tabs-line .show > .nav-link{
    border-bottom: 1px solid #7E8299 !important;
    color : #7E8299 !important;
}
</style>
