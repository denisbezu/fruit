import Grid from "@components/grid/grid";
import ReloadListActionExtension from "@components/grid/extension/reload-list-extension";
import ExportToSqlManagerExtension from "@components/grid/extension/export-to-sql-manager-extension";
import FiltersResetExtension from "@components/grid/extension/filters-reset-extension";
import SortingExtension from "@components/grid/extension/sorting-extension";

const $ = window.$;

class FruitsGrid {
  init() {
    const fruitsGrid = new Grid('fruits');

    fruitsGrid.addExtension(new ReloadListActionExtension());
    fruitsGrid.addExtension(new ExportToSqlManagerExtension());
    fruitsGrid.addExtension(new FiltersResetExtension());
    fruitsGrid.addExtension(new SortingExtension());
  }
}

$(() => {
  const fruitsGrid = new FruitsGrid();
  fruitsGrid.init();
});
