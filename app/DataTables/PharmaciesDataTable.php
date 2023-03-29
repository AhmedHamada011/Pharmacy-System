<?php

namespace App\DataTables;

use App\Models\Pharmacy;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Notifications\Action;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PharmaciesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    // protected Action = "<div class='btn-group btn-group-toggle' data-toggle='buttons'>
    //             <label class='btn btn-secondary active'>
    //             <a class='btn' id='option_a1' href='{{Route('pharmacies.edit',$name)}}'> edit
    //             </label>
    //             <label class='btn btn-secondary'>
    //             <a class='btn' id='option_a2' href='{{Route('pharmacies.show',$name)}}'> show
    //             </label>
    //             <label class='btn btn-secondary'>
    //             <a class='btn' id='option_a3' href='{{Route('pharmacies.destroy',$name)}}'> delete
    //             </label>
    //             </div>";
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', '<div class="btn-group btn-group-toggle" data-toggle="buttons">
                <a class="btn btn-success" id="option_a1" href="{{Route("pharmacies.edit",$id)}}"> edit
                </label>
                <a class="btn btn-primary" id="option_a2" href="{{Route("pharmacies.show",$id)}}"> show
                </label>
                <a class="btn btn-danger" id="option_a3" href="{{Route("pharmacies.destroy",$id)}}"> delete
                </label>
                </div>')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Pharmacy $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('pharmacies-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('area_id'),
            Column::make('owner_id'),
            Column::make('priority'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }
    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Pharmacies_' . date('YmdHis');
    }
}
