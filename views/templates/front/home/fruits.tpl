<div class="row">
  <div class="col-12">
    <table class="table table-bordered">
      <thead>
      <tr>
        <th>{l s='ID' mod='fruit' d='Modules.Fruit.Fruits'}</th>
        <th>{l s='Fruit name' mod='fruit' d='Modules.Fruit.Fruits'}</th>
        <th>{l s='Family' mod='fruit' d='Modules.Fruit.Fruits'}</th>
        <th>{l s='Order' mod='fruit' d='Modules.Fruit.Fruits'}</th>
        <th>{l s='Genus' mod='fruit' d='Modules.Fruit.Fruits'}</th>
        <th>{l s='Date add' mod='fruit' d='Modules.Fruit.Fruits'}</th>
      </tr>
      </thead>
      <tbody>
      {foreach $fruits as $fruit}
        <tr>
          <td>{$fruit.id}</td>
          <td>{$fruit.name}</td>
          <td>{$fruit.family}</td>
          <td>{$fruit.order}</td>
          <td>{$fruit.genus}</td>
          <td>{$fruit.date_add}</td>
        </tr>
      {/foreach}
      </tbody>
    </table>
  </div>
</div>
